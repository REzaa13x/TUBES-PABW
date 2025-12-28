<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Campaign;
use App\Models\DonationTransaction;
use App\Services\MidtransService;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function index($campaignId = null)
    {
        $campaign = null;
        $donaturCount = 0;
        $sisaHari = 0;

        // If a campaign ID is provided, try to fetch the campaign details
        if ($campaignId) {
            $campaign = Campaign::find($campaignId);

            if ($campaign) {
                // Hitung jumlah donatur (jumlah transaksi donasi yang telah diverifikasi untuk kampanye ini)
                $donaturCount = \App\Models\DonationTransaction::where('campaign_id', $campaign->id)
                    ->where('status', 'VERIFIED')
                    ->count();

                // Hitung sisa hari dari tanggal akhir kampanye
                $endDate = \Carbon\Carbon::parse($campaign->end_date);
                $sisaHari = $endDate->diffInDays() > 0 ? $endDate->diffInDays() : 0;
            }
        }

        return view('donation-details', compact('campaign', 'donaturCount', 'sisaHari'));
    }

    public function detail($campaignId = null)
    {
        $campaign = null;

        // If a campaign ID is provided, try to fetch the campaign details
        if ($campaignId) {
            $campaign = Campaign::find($campaignId);
        }

        return view('donation-detail', compact('campaign'));
    }

    public function checkout($campaignId = null)
    {
        $campaign = null;

        // If a campaign ID is provided, try to fetch the campaign details
        if ($campaignId) {
            $campaign = Campaign::find($campaignId);
        }

        return view('donation-checkout', compact('campaign'));
    }

    public function manualTransfer($order_id)
    {
        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Access denied. This transaction does not belong to you.');
        }

        return view('manual-transfer-instruction', compact('transaction'));
    }

    public function downloadTransactionPDF($order_id)
    {
        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        // Check if the transaction belongs to the authenticated user (or if user is admin)
        if ($transaction->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. This transaction does not belong to you.');
        }

        // Generate PDF using laravel-dompdf
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.transaction-instruction', compact('transaction'));

        return $pdf->download('instruksi-transfer-' . $order_id . '.pdf');
    }

    public function process(Request $request)
    {
        // Validate the donation data - new fields from donation-checkout
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'anonymous' => 'nullable|boolean',
            'payment_method' => 'required|in:bank_transfer,e_wallet,qris',
            'campaign_id' => 'nullable|exists:campaigns,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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

        // Define bank details for transfer
        $bank_account_name = config('app.bank_account_name', 'Organisasi Amal DonGiv');
        $bank_account_number = config('app.bank_account_number', '1234567890');
        $bank_name = config('app.bank_name', 'Bank Mandiri');

        // Calculate transfer deadline (24 hours from creation)
        $transfer_deadline = now()->addHours(24);

        $transaction = \App\Models\DonationTransaction::create([
            'order_id' => $order_id,
            'amount' => $request->amount,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'user_id' => auth()->id() ?? null, // Link to logged-in user, null if not authenticated
            'anonymous' => $request->anonymous ?? 0,
            'payment_method' => $request->payment_method,
            'payment_method_data' => json_encode($paymentMethodData),
            'status' => 'AWAITING_TRANSFER',
            'campaign_id' => $request->campaign_id,
            'transfer_deadline' => $transfer_deadline,
            'bank_account_name' => $bank_account_name,
            'bank_account_number' => $bank_account_number,
            'bank_name' => $bank_name
        ]);

        // Redirect to manual transfer instruction page
        return redirect()->route('donation.manual.transfer', ['order_id' => $order_id])
            ->with('success', 'Donasi berhasil dibuat! Silakan lakukan transfer dan upload bukti pembayaran.');
    }

    public function uploadProof(Request $request, $order_id)
    {
        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)->first();

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Access denied. This transaction does not belong to you.');
        }

        // Only allow uploading proof for transactions with status AWAITING_TRANSFER
        if ($transaction->status !== 'AWAITING_TRANSFER') {
            return redirect()->back()->with('error', 'Bukti transfer hanya bisa diupload untuk transaksi yang menunggu transfer');
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
                'status' => 'PENDING_VERIFICATION'  // This means it's added to history and waiting admin verification
            ]);

            return redirect()->back()->with('success', 'Bukti transfer berhasil diupload. Status donasi Anda saat ini: PENDING_VERIFICATION');
        }

        return redirect()->back()->with('error', 'Gagal mengupload bukti transfer');
    }

    /**
     * Admin index to show all donation transactions
     */
    public function adminIndex()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. You must be an admin to access this page.');
        }

        // Load all donation transactions with user and campaign data
        $transactions = \App\Models\DonationTransaction::with(['user', 'campaign'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.donations.index', compact('transactions'));
    }

    /**
     * Show single donation transaction for admin
     */
    public function showForAdmin($order_id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. You must be an admin to access this page.');
        }

        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)
            ->with(['user', 'campaign'])
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('admin.donations.show', compact('transaction'));
    }

    /**
     * Show invoice for admin to verify payment
     */
    public function showInvoiceForAdmin($order_id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. You must be an admin to access this page.');
        }

        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)
            ->with(['user', 'campaign'])
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('admin.donations.invoice', compact('transaction'));
    }

    /**
     * Process donation with Midtrans
     */
    public function processWithMidtrans(Request $request)
    {
        // Validate the donation data
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'anonymous' => 'nullable|boolean',
            'campaign_id' => 'nullable|exists:campaigns,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate unique order ID
        $order_id = 'DONATION-' . strtoupper(Str::random(10));

        // Create initial transaction record with pending status
        $transaction = DonationTransaction::create([
            'order_id' => $order_id,
            'amount' => $request->amount,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'user_id' => auth()->id() ?? null,
            'anonymous' => $request->anonymous ?? 0,
            'payment_method' => 'midtrans', // Use Midtrans as payment method
            'status' => 'PENDING', // Initial status is pending
            'campaign_id' => $request->campaign_id,
        ]);

        // Initialize Midtrans service
        $midtrans = new MidtransService();

        // Create Midtrans payment parameters
        $params = $midtrans->createDonationParams(
            $request->campaign_id ?? 0,
            $request->amount,
            $request->donor_name,
            $request->donor_email,
            $order_id
        );

        // Create payment request to Midtrans
        $paymentResponse = $midtrans->createPaymentRequest($params);

        if ($paymentResponse['success']) {
            // Redirect to Midtrans payment page
            return redirect($paymentResponse['redirect_url']);
        } else {
            // Delete the transaction if payment creation failed
            $transaction->delete();

            return redirect()->back()
                ->with('error', 'Gagal membuat pembayaran: ' . $paymentResponse['message']);
        }
    }

    /**
     * Handle Midtrans notification (webhook)
     */
    public function handleMidtransNotification(Request $request)
    {
        $notification = $request->all();

        $midtrans = new MidtransService();

        // Verify the signature
        if (!$midtrans->verifySignature($notification)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Get the order ID and status
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];

        // Update transaction status based on Midtrans response
        $transaction = DonationTransaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Map Midtrans status to our status
        $statusMap = [
            '200' => 'VERIFIED',        // Success
            '201' => 'PENDING',         // Pending
            '202' => 'PENDING_VERIFICATION', // Pending verification
            '406' => 'PENDING_VERIFICATION', // Challenge
            '407' => 'PENDING_VERIFICATION', // Deny
            '410' => 'PENDING_VERIFICATION', // Expired
            '411' => 'PENDING_VERIFICATION', // Failed
        ];

        $status = $statusMap[$statusCode] ?? 'PENDING_VERIFICATION';

        // Get the old status before updating
        $oldStatus = $transaction->status;

        // Update transaction details
        $transaction->update([
            'status' => $status,
            'payment_method' => $notification['payment_type'] ?? 'midtrans',
            'payment_method_data' => json_encode($notification),
        ]);

        // If status is changing to VERIFIED (Paid) from a non-verified status, update campaign amount
        if ($status === 'VERIFIED' && $oldStatus !== 'VERIFIED' && $transaction->campaign_id) {
            // Add the donation amount to the campaign's current amount
            $campaign = \App\Models\Campaign::find($transaction->campaign_id);
            if ($campaign) {
                $campaign->increment('current_amount', $transaction->amount);
            }
        }
        // If status is changing from VERIFIED to something else, subtract the donation amount
        elseif ($oldStatus === 'VERIFIED' && $status !== 'VERIFIED' && $transaction->campaign_id) {
            $campaign = \App\Models\Campaign::find($transaction->campaign_id);
            if ($campaign) {
                $campaign->decrement('current_amount', $transaction->amount);
            }
        }

        // Award coins if transaction is verified
        if ($status === 'VERIFIED' && $transaction->user) {
            $transaction->user->addCoins(1, 'donation_completed');
        }

        return response()->json(['message' => 'Notification handled successfully']);
    }

    /**
     * Update transaction status - for admin use
     */
    public function updateStatus(Request $request, $order_id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. You must be an admin to update transaction status.');
        }

        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        $request->validate([
            'status' => 'required|in:AWAITING_TRANSFER,PENDING_VERIFICATION,VERIFIED,CANCELLED'
        ]);

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
            'VERIFIED' => 'Pembayaran berhasil diverifikasi. Donasi telah diproses dan poin kebaikan ditambahkan.',
            'CANCELLED' => 'Pembayaran ditolak. Donasi dibatalkan.',
            'PENDING_VERIFICATION' => 'Status pembayaran diubah menjadi menunggu verifikasi.',
            'AWAITING_TRANSFER' => 'Status pembayaran diubah menjadi menunggu transfer.'
        ];

        $message = $statusMessages[$request->status] ?? 'Status transaksi berhasil diperbarui';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete a donation transaction - for admin use
     */
    public function destroy($order_id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. You must be an admin to delete transaction.');
        }

        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)->first();

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found');
        }

        // Delete the transaction
        $transaction->delete();

        return redirect()->back()->with('success', 'Donasi berhasil dihapus');
    }
}