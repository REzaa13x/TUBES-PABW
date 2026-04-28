<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Campaign;

class CampaignController extends Controller
{
    // ...existing code...

    /**
     * Store a new donation campaign from user profile (AJAX/form)
     */
    public function storeFromProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'target' => 'required|numeric|min:10000',
            'minimal' => 'nullable|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'lokasi' => 'required|string|max:100',
            'penerima' => 'required|string|max:255',
            'jenis_penerima' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:4096',
            'whatsapp' => 'required|string|max:20',
            'penyaluran' => 'required|string',
            'validasi_data' => 'accepted',
            'publik' => 'nullable|boolean',
            'relawan' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Handle file upload
        $fotoPath = $request->file('foto')->store('campaigns', 'public');
        $dokumenPath = $request->hasFile('dokumen') ? $request->file('dokumen')->store('campaigns/docs', 'public') : null;

        $campaign = Campaign::create([
            'title' => $request->judul,
            'kategori' => $request->kategori,
            'description' => $request->deskripsi,
            'target_amount' => $request->target,
            'current_amount' => 0,
            'image' => $fotoPath,
            'end_date' => $request->deadline,
            'user_id' => $user->id,
            'slug' => Str::slug($request->judul) . '-' . Str::random(5),
            'status' => 'Menunggu Verifikasi',
            'yayasan' => $request->jenis_penerima === 'Yayasan' ? $request->penerima : null,
        ]);

        // Simpan info tambahan ke tabel lain jika perlu (atau gunakan kolom JSON/relasi)
        // ...

        // Bisa juga simpan dokumen ke relasi lain jika diperlukan

        return response()->json(['success' => true, 'message' => 'Donasi berhasil diajukan, menunggu verifikasi.', 'campaign_id' => $campaign->id]);
    }
}
