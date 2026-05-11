<?php

namespace App\Http\Controllers;

use App\Models\DonationTransaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = config('midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            $transaction = DonationTransaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            if ($transactionStatus == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $transaction->update(['status' => 'PENDING_VERIFICATION']);
                    } else {
                        $this->markAsPaid($transaction);
                    }
                }
            } else if ($transactionStatus == 'settlement') {
                $this->markAsPaid($transaction);
            } else if ($transactionStatus == 'pending') {
                $transaction->update(['status' => 'AWAITING_TRANSFER']);
            } else if ($transactionStatus == 'deny') {
                $transaction->update(['status' => 'CANCELLED']);
            } else if ($transactionStatus == 'expire') {
                $transaction->update(['status' => 'CANCELLED']);
            } else if ($transactionStatus == 'cancel') {
                $transaction->update(['status' => 'CANCELLED']);
            }

            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function markAsPaid($transaction)
    {
        if ($transaction->status !== 'VERIFIED') {
            $transaction->update([
                'status' => 'VERIFIED',
                'verified_at' => now(),
            ]);

            // Update campaign current amount
            if ($transaction->campaign) {
                $transaction->campaign->increment('current_amount', $transaction->amount);
            }

            // Award coins to user
            if ($transaction->user) {
                $transaction->user->addCoins(1, 'donation_completed');
            }
        }
    }
}
