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

        // 1. Ambil Data Pagination (Untuk Tab Riwayat)
        $donations = $user->donations()
            ->latest()
            ->paginate(5, ['*'], 'donations_page');

        $volunteerApps = $user->volunteerApplications()
            ->with('campaign') 
            ->latest()
            ->paginate(5, ['*'], 'volunteer_page');

        // 2. HITUNG TOTAL UANG DONASI (Hanya yang statusnya 'paid')
        $totalDonationAmount = $user->donations()
            ->where('status', 'paid')
            ->sum('amount');

        // 3. HITUNG POIN KEBAIKAN
        // Logika: (Jumlah Donasi Sukses + Jumlah Relawan Diterima) * 10
        $countPaidDonations = $user->donations()->where('status', 'paid')->count();
        $countApprovedVolunteer = $user->volunteerApplications()->where('status', 'approved')->count();
        
        $totalPoints = ($countPaidDonations + $countApprovedVolunteer) * 10;

        return view('profiles.index', compact(
            'user', 
            'donations', 
            'volunteerApps', 
            'totalDonationAmount', 
            'totalPoints'
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
        $request->validate([
            'name' => 'required|string|max:255',
            // Email divalidasi tapi tidak diupdate jika read-only di view, 
            // tapi tetap kita validasi untuk keamanan.
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        // $user->email = $request->email; // Opsional: Hapus baris ini jika email benar-benar dilarang ganti
        // Tapi biasanya kita biarkan tersimpan ulang untuk konsistensi data
        $user->phone = $request->phone;
        $user->address = $request->alamat; // Pastikan kolom database Anda 'address' atau sesuaikan

        // Upload Foto
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::exists('public/' . $user->photo)) {
                Storage::delete('public/' . $user->photo);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->photo = $path;
        }

        $user->save();

        return back()->with('success', 'Informasi profil berhasil diperbarui!');
    }
}
}