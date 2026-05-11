<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use Carbon\Carbon;

class VerificationController extends Controller
{
    public function __construct()
    {
        // Mewajibkan login untuk akses halaman verifikasi
        $this->middleware('auth');
    }

    public function show(Request $request, $token)
    {
        // Cari kampanye berdasarkan token unik
        $campaign = Campaign::where('verification_token', $token)->first();

        // Jika kampanye tidak ditemukan ATAU sudah diverifikasi (status sudah bukan pending)
        if (!$campaign || strtolower($campaign->status) !== 'pending') {
            // Jika kampanye ditemukan tapi sudah aktif atau diverifikasi, arahkan ke halaman sukses
            if ($campaign && in_array(strtolower($campaign->status), ['active', 'verified'])) {
                return redirect()->route('verify.success')->with('success', 'Kampanye ini sudah berhasil diverifikasi.');
            }
            return redirect('/')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        $user = auth()->user();
        
        // Ambil riwayat campaign yang pernah diverifikasi oleh user ini secara resmi
        $history = Campaign::where('validator_user_id', $user->id)
            ->whereIn('status', ['verified', 'active'])
            ->latest('verified_at')
            ->get();

        return view('verify.show', compact('campaign', 'user', 'history'));
    }

    public function verify(Request $request, $token)
    {
        $user = auth()->user();

        // Pastikan hanya role validator yang bisa memverifikasi
        if (strtolower($user->role) !== 'validator') {
            return redirect()->back()->with('error', 'Akses Ditolak: Hanya akun dengan Role Validator yang dapat melakukan verifikasi kampanye.');
        }

        $campaign = Campaign::where('verification_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $campaign->update([
            'status' => 'verified', // Ubah status menjadi verified setelah verifikasi
            'validator_name' => $user->name,
            'validator_user_id' => $user->id,
            'verified_at' => Carbon::now(),
            'verification_token' => null, // Token hangus setelah digunakan (sekali pakai)
        ]);

        return redirect()->route('verify.success')->with('success', 'Verifikasi Berhasil! Kampanye telah resmi diverifikasi dan kini berstatus Verified.');
    }

    public function success()
    {
        $user = auth()->user();
        
        // Ambil semua kampanye yang pernah diverifikasi oleh user ini secara resmi
        $history = Campaign::where('validator_user_id', $user->id)
            ->whereIn('status', ['verified', 'active'])
            ->latest('verified_at')
            ->get();

        return view('verify.success', compact('user', 'history'));
    }
}
