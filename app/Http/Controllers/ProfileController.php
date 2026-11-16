<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil Data Donasi (Untuk Tab Riwayat Donasi Terpadu)
        // New donation transactions (including manual transfers that need proof upload)
        $donationTransactionsList = \App\Models\DonationTransaction::where('user_id', $user->id)
            ->with(['campaign'])
            ->latest()
            ->get();

        // Old donations from the donations table (if any)
        $legacyDonationsList = \App\Models\Donation::where('user_id', $user->id)
            ->with(['campaign'])
            ->latest()
            ->get();

        // Combined history for unified view - merge both collections without duplicate order_id
        $allDonations = collect();

        foreach($donationTransactionsList as $transaction) {
            $allDonations->push([
                'id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'amount' => $transaction->amount,
                'campaign' => $transaction->campaign ?? null,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at,
                'type' => 'transaction',
                'model' => $transaction
            ]);
        }

        $existingOrderIds = $donationTransactionsList->pluck('order_id')->toArray();
        foreach($legacyDonationsList as $donation) {
            if (!in_array($donation->order_id, $existingOrderIds)) {
                $allDonations->push([
                    'id' => $donation->id,
                    'order_id' => $donation->order_id,
                    'amount' => $donation->amount,
                    'campaign' => $donation->campaign ?? null,
                    'status' => $donation->status,
                    'created_at' => $donation->created_at,
                    'type' => 'legacy',
                    'model' => $donation
                ]);
            }
        }

        // Sort by created_at descending
        $allDonations = $allDonations->sortByDesc('created_at')->values();

        // Pagination instances for compatibility
        $donations = \App\Models\Donation::where('user_id', $user->id)->latest()->paginate(5, ['*'], 'donations_page');
        $donationTransactions = \App\Models\DonationTransaction::where('user_id', $user->id)->with(['campaign'])->latest()->paginate(5, ['*'], 'donation_transactions_page');

        $volunteerApps = $user->volunteerApplications()
            ->with('campaign')
            ->latest()
            ->paginate(5, ['*'], 'volunteer_page');

        // 2. HITUNG TOTAL UANG DONASI (Hanya yang statusnya 'paid' atau 'VERIFIED')
        $totalDonationAmount = $user->donations()
            ->where('status', 'paid')
            ->sum('amount');

        // Also include verified donation transactions
        $totalDonationAmount += \App\Models\DonationTransaction::where('user_id', $user->id)
            ->where('status', 'VERIFIED')
            ->sum('amount');

        // 3. HITUNG POIN KEBAIKAN
        // Logika: (Jumlah Donasi Sukses + Jumlah Relawan Diterima) * 10
        $countPaidDonations = $user->donations()->where('status', 'paid')->count();
        $countVerifiedDonationTransactions = \App\Models\DonationTransaction::where('user_id', $user->id)
            ->where('status', 'VERIFIED')
            ->count();
        $countApprovedVolunteer = $user->volunteerApplications()->where('status', 'approved')->count();

        $totalPoints = ($countPaidDonations + $countVerifiedDonationTransactions + $countApprovedVolunteer) * 10;

        // 4. HITUNG JUMLAH KAMPANYE YANG DIDONASI
        $countDonatedCampaigns = $user->donations()
            ->where('status', 'paid')
            ->distinct('campaign_id')
            ->count('campaign_id');

        $countDonatedCampaigns += \App\Models\DonationTransaction::where('user_id', $user->id)
            ->where('status', 'VERIFIED')
            ->whereNotNull('campaign_id')
            ->distinct('campaign_id')
            ->count('campaign_id');

        // 5. HITUNG JUMLAH KAMPANYE RELAWAN YANG DI IKUTI
        $countVolunteerCampaigns = $user->volunteerApplications()
            ->where('status', 'approved')
            ->distinct('volunteer_campaign_id')
            ->count('volunteer_campaign_id');

        // Get donation transactions for detailed history (same as showTransactionHistory method)
        $donationTransactions = \App\Models\DonationTransaction::where('user_id', $user->id)
            ->with(['campaign']) // Load campaign relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Also get any donations from the old donations table if they exist
        $legacyDonations = \App\Models\Donation::where('user_id', $user->id)
            ->with(['campaign'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $selectedPreferences = $user->categoryPreferences()
            ->where('is_preferred', true)
            ->pluck('category')
            ->toArray();

        $autoDiscoveredPreferences = $user->categoryPreferences()
            ->where('auto_discovered', true)
            ->get();

        // Ambil kampanye donasi yang dibuat oleh user ini
        $myCampaigns = \App\Models\Campaign::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil sertifikat milik user ini
        $certificates = \App\Models\VolunteerCertificate::where('volunteer_id', $user->id)
            ->with('campaign')
            ->latest()
            ->get();


        return view('profiles.index', compact(
            'user',
            'donations',
            'volunteerApps',
            'totalDonationAmount',
            'totalPoints',
            'countDonatedCampaigns',
            'countVolunteerCampaigns',
            'donationTransactions',
            'legacyDonations',
            'allDonations',
            'myCampaigns', // <-- Variable ini sekarang sudah ada isinya
            'selectedPreferences',
            'autoDiscoveredPreferences',
            'certificates'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Cek apakah ini update PASSWORD atau update PROFIL
        if ($request->has('update_password')) {
            
            // --- LOGIKA UPDATE PASSWORD ---
            $request->validate([
                'current_password' => 'nullable|current_password', // Opsional: Cek password lama (aman)
                'password' => 'required|min:8|confirmed',
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Kata sandi berhasil diperbarui!');

        } else {

            // --- LOGIKA UPDATE PROFIL ---
            // Check if this is a photo-only update (AJAX request with only photo field)
            if ($request->hasFile('photo') && !$request->has('name') && !$request->has('email')) {
                // Only validate the photo field for photo-only updates
                $request->validate([
                    'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                // Upload Foto only
                if ($request->hasFile('photo')) {
                    \Log::info('Processing photo upload for user: ' . $user->id);

                    if ($user->photo && Storage::exists('public/' . $user->photo)) {
                        \Log::info('Deleting old photo: ' . $user->photo);
                        Storage::delete('public/' . $user->photo);
                    }  // End of nested if to delete old photo

                    $file = $request->file('photo');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('profile-photos', $filename, 'public');

                    \Log::info('New photo stored at: ' . $path);

                    // Simpan path lengkap ke database
                    $user->photo = $path;

                    \Log::info('Photo field set to: ' . $user->photo);
                }  // End of if to upload photo

                $user->save();

                // Check if request is AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Foto profil berhasil diperbarui!',
                        'data' => [
                            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'address' => $user->alamat ?? null
                        ]
                    ]);
                }

                return back()->with('success', 'Foto profil berhasil diperbarui!');
            } else {
                // Full profile update - validate all fields
                $request->validate([
                    'name' => 'required|string|max:255',
                    // Email divalidasi tapi tidak diupdate jika read-only di view,
                    // tapi tetap kita validasi untuk keamanan.
                    'email' => 'required|email|unique:users,email,'.$user->id,
                    'phone' => 'nullable|string|max:15',
                    'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                $user->name = $request->name;
                // $user->email = $request->email; // Opsional: Hapus baris ini jika email benar-benar dilarang ganti
                // Tapi biasanya kita biarkan tersimpan ulang untuk konsistensi data
                $user->phone = $request->phone;
                // Note: 'alamat' field doesn't exist in the users table, so we skip it
                // If you need address field, you'll need to create a migration to add it

                // Upload Foto
                if ($request->hasFile('photo')) {
                    \Log::info('Processing photo upload for user: ' . $user->id);

                    if ($user->photo && Storage::exists('public/' . $user->photo)) {
                        \Log::info('Deleting old photo: ' . $user->photo);
                        Storage::delete('public/' . $user->photo);
                    }  // End of nested if to delete old photo

                    $file = $request->file('photo');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('profile-photos', $filename, 'public');

                    \Log::info('New photo stored at: ' . $path);

                    // Simpan path lengkap ke database
                    $user->photo = $path;

                    \Log::info('Photo field set to: ' . $user->photo);
                }  // End of if to upload photo

                $user->save();

                // Check if request is AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Informasi profil berhasil diperbarui!',
                        'data' => [
                            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'address' => $user->alamat ?? null
                        ]
                    ]);
                }

                return back()->with('success', 'Informasi profil berhasil diperbarui!');
            }
        }  // Closes the else block
    }  // Closes the update method

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'string|in:Pendidikan,Kesehatan,Sosial,Lingkungan,Bencana,Keagamaan'
        ]);

        $user = Auth::user();
        $newCategories = $request->input('categories', []);

        // Hapus preferensi manual lama (yang bukan auto_discovered)
        $user->categoryPreferences()->where('auto_discovered', false)->delete();
        
        // Reset status is_preferred untuk yang auto_discovered
        $user->categoryPreferences()->where('auto_discovered', true)->update(['is_preferred' => false]);

        // Tambah/Update preferensi baru
        foreach ($newCategories as $category) {
            \App\Models\VolunteerCategoryPreference::updateOrCreate(
                ['user_id' => $user->id, 'category' => $category],
                [
                    'is_preferred' => true,
                    'auto_discovered' => false,
                ]
            );
        }

        // Jalankan auto-discovery dari riwayat juga agar selalu sinkron
        $recController = new \App\Http\Controllers\Api\VolunteerRecommendationController();
        $recController->autoDiscoverCategories($user);

        return back()->with('success', 'Preferensi minat relawan berhasil disimpan!');
    }

    public function showTransactionHistory()
    {
        $user = Auth::user();

        // Get donation transactions for the user, including both old donations table and new donation_transactions table
        $donationTransactions = \App\Models\DonationTransaction::where('user_id', $user->id)
            ->with(['campaign']) // Load campaign relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Also get any donations from the old donations table if they exist
        $donations = \App\Models\Donation::where('user_id', $user->id)
            ->with(['campaign'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profiles.transactions', compact('donationTransactions', 'donations'));
    }

    /**
     * Upload proof of payment for a donation transaction
     */
    public function uploadProof(Request $request, $order_id)
    {
        $user = Auth::user();

        // Find the transaction that belongs to the user
        $transaction = \App\Models\DonationTransaction::where('user_id', $user->id)
            ->where('order_id', $order_id)
            ->first();

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau tidak milik Anda');
        }

        // Only allow uploading proof for transactions with status AWAITING_TRANSFER or PENDING_VERIFICATION
        // and only if no proof has been uploaded yet
        if (!in_array($transaction->status, ['AWAITING_TRANSFER', 'PENDING_VERIFICATION'])) {
            return redirect()->back()->with('error', 'Bukti transfer hanya bisa diupload untuk transaksi yang menunggu transfer atau sedang diverifikasi');
        }

        // Check if proof already exists
        if ($transaction->proof_of_transfer_path) {
            return redirect()->back()->with('error', 'Bukti transfer sudah pernah diupload sebelumnya');
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

            return redirect()->back()->with('success', 'Bukti transfer berhasil diupload. Status donasi Anda saat ini: PENDING_VERIFICATION');
        }

        return redirect()->back()->with('error', 'Gagal mengupload bukti transfer');
    }

    public function invoice($id)
    {
        $user = Auth::user();

        // Try to find the transaction in DonationTransaction table first
        $transaction = \App\Models\DonationTransaction::where('user_id', $user->id)
            ->where('id', $id)
            ->with('campaign')
            ->first();

        if ($transaction) {
            // If found in donation transactions, return the donation transaction invoice view
            return view('profiles.invoice', compact('transaction'));
        }

        // If not found, try the old donation table
        $donation = \App\Models\Donation::where('user_id', $user->id)
            ->where('id', $id)
            ->with('campaign')
            ->first();

        if ($donation) {
            // If found in old donations, return the donation invoice view
            return view('profiles.invoice', [
                'transaction' => $donation,
                'isOldDonation' => true
            ]);
        }

        // If not found in either table, return error
        abort(404, 'Invoice not found');
    }
}