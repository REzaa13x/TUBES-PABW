<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonationTransaction;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    /**
     * Create a new donation
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'anonymous' => 'nullable|boolean',
            'payment_method' => 'required|in:bank_transfer,e_wallet,qris',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'selected_bank' => 'nullable|required_if:payment_method,bank_transfer',
            'selected_ewallet' => 'nullable|required_if:payment_method,e_wallet',
            'selected_qris' => 'nullable|required_if:payment_method,qris',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Collect payment method specific data
        $paymentMethodData = [];

        if ($request->payment_method === 'bank_transfer' && $request->selected_bank) {
            $paymentMethodData['selected_bank'] = $request->selected_bank;
        } elseif ($request->payment_method === 'e_wallet' && $request->selected_ewallet) {
            $paymentMethodData['selected_ewallet'] = $request->selected_ewallet;
        } elseif ($request->payment_method === 'qris' && $request->selected_qris) {
            $paymentMethodData['selected_qris'] = $request->selected_qris;
        }

        // Generate unique order ID
        $order_id = 'ORD-' . strtoupper(Str::random(10));

        // Define bank details for transfer (for bank_transfer method)
        $bank_account_name = config('app.bank_account_name', 'Organisasi Amal DonGiv');
        $bank_account_number = config('app.bank_account_number', '1234567890');
        $bank_name = config('app.bank_name', 'Bank Mandiri');

        // Calculate transfer deadline (24 hours from creation) for bank transfers
        $transfer_deadline = null;
        if ($request->payment_method === 'bank_transfer') {
            $transfer_deadline = now()->addHours(24);
        }

        $transaction = DonationTransaction::create([
            'order_id' => $order_id,
            'amount' => $request->amount,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'user_id' => $user->id, // Link to logged-in user
            'anonymous' => $request->anonymous ?? 0,
            'payment_method' => $request->payment_method,
            'payment_method_data' => json_encode($paymentMethodData),
            'status' => 'AWAITING_TRANSFER', // This status applies to all payment methods initially
            'campaign_id' => $request->campaign_id,
            'transfer_deadline' => $transfer_deadline,
            'bank_account_name' => $bank_account_name,
            'bank_account_number' => $bank_account_number,
            'bank_name' => $bank_name
        ]);

        return response()->json([
            'message' => 'Donation created successfully',
            'data' => $transaction
        ], 201);
    }

    /**
     * Upload proof of payment for a donation
     */
    public function uploadProof(Request $request, string $order_id): JsonResponse
    {
        $user = $request->user();

        $transaction = DonationTransaction::where('order_id', $order_id)->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== $user->id) {
            return response()->json([
                'message' => 'Access denied. This transaction does not belong to you.'
            ], 403);
        }

        // Only allow uploading proof for transactions with status AWAITING_TRANSFER or PENDING_VERIFICATION
        if (!in_array($transaction->status, ['AWAITING_TRANSFER', 'PENDING_VERIFICATION'])) {
            return response()->json([
                'message' => 'Proof of payment can only be uploaded for transactions awaiting transfer or pending verification'
            ], 400);
        }

        // Check if proof already exists
        if ($transaction->proof_of_transfer_path) {
            return response()->json([
                'message' => 'Proof of payment has already been uploaded for this transaction'
            ], 400);
        }

        $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('proof')) {
            $file = $request->file('proof');
            $filename = 'transfer-proof-' . $order_id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('transfer-proofs', $filename, 'public');

            $transaction->update([
                'proof_of_transfer_path' => $path,
                'status' => 'PENDING_VERIFICATION'  // Update status to pending verification
            ]);

            return response()->json([
                'message' => 'Proof of payment uploaded successfully. Status updated to pending verification.',
                'data' => $transaction
            ]);
        }

        return response()->json([
            'message' => 'Failed to upload proof of payment'
        ], 400);
    }


    /**
     * Get user's donation history
     */
    public function getUserDonations(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $donations = DonationTransaction::where('user_id', $user->id)
            ->with('campaign')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'message' => 'User donations retrieved successfully',
            'data' => $donations
        ]);
    }

    /**
     * Get donation detail
     */
    public function show(Request $request, string $order_id): JsonResponse
    {
        $user = $request->user();
        
        $transaction = DonationTransaction::where('order_id', $order_id)
            ->where('user_id', $user->id)
            ->with('campaign')
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Donation not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Donation retrieved successfully',
            'data' => $transaction
        ]);
    }

    /**
     * Get all donations (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Check if user is admin
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }

        $donations = DonationTransaction::with(['user', 'campaign'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'message' => 'Donations retrieved successfully',
            'data' => $donations
        ]);
    }

    /**
     * Update donation status (admin only)
     */
    public function updateStatus(Request $request, string $order_id): JsonResponse
    {
        $user = $request->user();
        
        // Check if user is admin
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:AWAITING_TRANSFER,PENDING_VERIFICATION,VERIFIED,CANCELLED'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction = DonationTransaction::where('order_id', $order_id)->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        $oldStatus = $transaction->status;
        $transaction->update(['status' => $request->status]);

        // If status is changing to VERIFIED (Paid) from a non-verified status, update campaign amount
        if ($request->status === 'VERIFIED' && $oldStatus !== 'VERIFIED' && $transaction->campaign_id) {
            // Add the donation amount to the campaign's current amount
            $campaign = \App\Models\Campaign::find($transaction->campaign_id);
            if ($campaign) {
                $campaign->increment('current_amount', $transaction->amount);
            }
        }
        // If status is changing from VERIFIED to something else, subtract the donation amount
        elseif ($oldStatus === 'VERIFIED' && $request->status !== 'VERIFIED' && $transaction->campaign_id) {
            $campaign = \App\Models\Campaign::find($transaction->campaign_id);
            if ($campaign) {
                $campaign->decrement('current_amount', $transaction->amount);
            }
        }

        // Award coins if transaction is marked as VERIFIED (Paid) and was previously not verified
        if ($request->status === 'VERIFIED' && $oldStatus !== 'VERIFIED' && $transaction->user) {
            $transaction->user->addCoins(1, 'donation_completed');
        }

        // Determine success message based on status
        $statusMessages = [
            'VERIFIED' => 'Payment successfully verified. Donation processed and kindness points added.',
            'CANCELLED' => 'Payment rejected. Donation cancelled.',
            'PENDING_VERIFICATION' => 'Payment status changed to pending verification.',
            'AWAITING_TRANSFER' => 'Payment status changed to awaiting transfer.'
        ];

        $message = $statusMessages[$request->status] ?? 'Transaction status updated successfully';

        return response()->json([
            'message' => $message,
            'data' => $transaction
        ]);
    }

    /**
     * Get donation detail for admin (with proof image)
     */
    public function showForAdmin(Request $request, string $order_id): JsonResponse
    {
        $user = $request->user();

        // Check if user is admin
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }

        $transaction = DonationTransaction::where('order_id', $order_id)
            ->with(['user', 'campaign'])
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Donation not found'
            ], 404);
        }

        // Add proof image URL if proof exists
        if ($transaction->proof_of_transfer_path) {
            $transaction->proof_url = asset('storage/' . $transaction->proof_of_transfer_path);
        }

        return response()->json([
            'message' => 'Donation retrieved successfully',
            'data' => $transaction
        ]);
    }
}