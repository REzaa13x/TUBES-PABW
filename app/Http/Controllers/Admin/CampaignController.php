<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VolunteerCampaign;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    // 1. READ (Menampilkan Daftar)
    public function index()
    {
        $campaigns = VolunteerCampaign::latest()->paginate(10);
        return view('admin.relawan.index', compact('campaigns'));
    }

    // 2. CREATE (Menampilkan Form Tambah)
    public function create()
    {
        return view('admin.relawan.create');
    }

    // 3. STORE (Menyimpan Data Baru)
    public function store(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'judul'           => 'required|max:255', // Pastikan name="judul" di form HTML
            'kategori'        => 'required',
            'lokasi'          => 'required',
            'kuota_total'     => 'required|numeric|min:1',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi'       => 'required',
            'image'           => 'required|image|file|max:2048'
        ]);

        // Upload Gambar
        if ($request->file('image')) {
            $validated['image'] = $request->file('image')->store('campaign-images', 'public');
        }

        // Menggunakan 'judul' sebagai dasar slug + timestamp agar unik
        $validated['slug'] = Str::slug($request->judul) . '-' . time();

        // Set nilai default lainnya
        $validated['kuota_terisi'] = 0;
        $validated['status'] = 'Aktif';

        // Simpan ke Database
        VolunteerCampaign::create($validated);

        return redirect()->route('admin.relawan.index')
            ->with('success', 'Kampanye berhasil diterbitkan! Data kini tampil di halaman publik.');
    }

    // 4. EDIT (Menampilkan Form Edit)
    public function edit($id)
    {
        $campaign = VolunteerCampaign::findOrFail($id);
        return view('admin.relawan.edit', compact('campaign'));
    }

    // 5. SHOW (Menampilkan Detail Kampanye Relawan)
    public function show($id)
    {
        $campaign = VolunteerCampaign::with('volunteers')->findOrFail($id);
        $verifiedCount = $campaign->volunteers->whereIn('status_verifikasi', ['disetujui', 'Terverifikasi'])->count();
        $pendingCount = $campaign->volunteers->whereIn('status_verifikasi', ['pending', 'Menunggu Verifikasi'])->count();
        return view('admin.relawan.show', compact('campaign', 'verifiedCount', 'pendingCount'));
    }

    // 5. UPDATE (Menyimpan Perubahan)
    public function update(Request $request, $id)
    {
        $campaign = VolunteerCampaign::findOrFail($id);

        $validated = $request->validate([
            'judul'           => 'required|max:255',
            'kategori'        => 'required',
            'lokasi'          => 'required',
            'kuota_total'     => 'required|numeric|min:1',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi'       => 'required',
            'image'           => 'nullable|image|file|max:2048',
            'status'          => 'nullable|in:Aktif,Nonaktif',
        ]);

        // Cek jika ada gambar baru yang diupload
        if ($request->file('image')) {
            // Hapus gambar lama
            if ($campaign->image) {
                Storage::delete('public/' . $campaign->image);
            }
            // Simpan gambar baru
            $validated['image'] = $request->file('image')->store('campaign-images', 'public');
        }

        // Update slug jika judul berubah
        if ($request->judul != $campaign->judul) {
            $validated['slug'] = Str::slug($request->judul) . '-' . time();
        }

        // Update Database
        $campaign->update($validated);

        $message = isset($validated['status']) && $validated['status'] !== $campaign->getOriginal('status')
            ? 'Status kampanye berhasil diubah menjadi ' . $validated['status'] . '!'
            : 'Kampanye berhasil diperbarui!';

        return redirect()->route('admin.relawan.index')
            ->with('success', $message);
    }

    // 6. DESTROY (Menghapus Data)
    public function destroy($id)
    {
        $campaign = VolunteerCampaign::findOrFail($id);

        // Hapus gambar dari storage
        if ($campaign->image) {
            Storage::delete('public/' . $campaign->image);
        }

        $campaign->delete();

        return redirect()->route('admin.relawan.index')
            ->with('success', 'Kampanye telah dihapus.');
    }
}