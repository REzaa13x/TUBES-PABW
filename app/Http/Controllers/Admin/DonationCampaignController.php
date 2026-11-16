<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationCampaignController extends Controller
{
    // 1. READ (Menampilkan Daftar)
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(10);
        $validators = \App\Models\ValidatorContact::all();
        return view('admin.campaigns.index', compact('campaigns', 'validators'));
    }

    // 2. CREATE (Menampilkan Form Tambah)
    public function create()
    {
        return view('admin.campaigns.create');
    }

    // 3. STORE (Menyimpan Data Baru)
    public function store(Request $request)
    {
        try {
            // Validasi Input
            $validated = $request->validate([
                'title'           => 'required|max:255',
                'description'     => 'required',
                'target_amount'   => 'required|numeric|min:1',
                'current_amount'  => 'nullable|numeric|min:0',
                'status'          => 'required|in:pending,verified,rejected',
                'kategori'        => 'required|in:Lingkungan,Kesehatan,Pendidikan,Sosial Kemanusiaan,Bencana Alam,Ibadah',
                'yayasan'         => 'nullable|string|max:255',
                'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'end_date'        => 'required|date|after_or_equal:'.date('Y-m-d'),
                'lokasi'          => 'required|string',
                'provinsi'        => 'nullable|string|max:255',
                'kabupaten'       => 'nullable|string|max:255',
                'kecamatan'       => 'nullable|string|max:255',
                'alamat_lengkap'  => 'nullable|string',
                'kode_pos'        => 'nullable|string|max:10',
            ]);

            // Upload Gambar
            if ($request->file('image')) {
                $validated['image'] = $request->file('image')->store('campaigns', 'public');
            }

            // Set default values if not provided
            $validated['current_amount'] = $validated['current_amount'] ?? 0;

            // Generate slug from title
            $validated['slug'] = Str::slug($request->title) . '-' . time();

            // Set user_id to currently authenticated admin
            $validated['user_id'] = auth()->id();

            // Generate verification token if status is Pending (case-insensitive)
            if (strtolower($validated['status']) === 'pending') {
                $validated['verification_token'] = Str::uuid()->toString();
            }

            // --- PERBAIKAN: CEK DUPLIKASI (IDEMPOTENCY) ---
            $existing = Campaign::where('user_id', auth()->id())
                ->where('title', $request->title)
                ->where('created_at', '>=', now()->subMinutes(1))
                ->first();

            if ($existing) {
                return redirect()->route('admin.campaigns.index')
                    ->with('success', 'Kampanye sudah diterbitkan sebelumnya.');
            }

            // Simpan ke Database
            Campaign::create($validated);

            return redirect()->route('admin.campaigns.index')
                ->with('success', 'Kampanye donasi berhasil diterbitkan! Data kini tampil di halaman publik.');
        } catch (\Exception $e) {
            \Log::error('Error creating campaign: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal membuat kampanye: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // 4. EDIT (Menampilkan Form Edit)
    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('admin.campaigns.edit', compact('campaign'));
    }

    // 5. SHOW (Menampilkan Detail Kampanye)
    public function show($id)
    {
        // Load campaign with related user (no validatorContact relationship exists)
        $campaign = Campaign::with(['user'])->findOrFail($id);
        return view('admin.campaigns.show', compact('campaign'));
    }

    // 6. UPDATE (Memperbarui Data Kampanye)
    public function update(Request $request, $id)
    {
        try {
            $campaign = Campaign::findOrFail($id);

            // Validasi Input
            $validated = $request->validate([
                'title'           => 'required|max:255',
                'description'     => 'required',
                'target_amount'   => 'required|numeric|min:1',
                'current_amount'  => 'nullable|numeric|min:0',
                'status'          => 'required|in:pending,verified,rejected',
                'kategori'        => 'required|in:Lingkungan,Kesehatan,Pendidikan,Sosial Kemanusiaan,Bencana Alam,Ibadah',
                'yayasan'         => 'nullable|string|max:255',
                'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'end_date'        => 'required|date',
                'lokasi'          => 'required|string',
                'provinsi'        => 'nullable|string|max:255',
                'kabupaten'       => 'nullable|string|max:255',
                'kecamatan'       => 'nullable|string|max:255',
                'alamat_lengkap'  => 'nullable|string',
                'kode_pos'        => 'nullable|string|max:10',
            ]);

            // Ganti Gambar Jika Diunggah
            if ($request->hasFile('image')) {
                $rawOldImage = $campaign->getRawOriginal('image');
                if ($rawOldImage && Storage::disk('public')->exists($rawOldImage)) {
                    Storage::disk('public')->delete($rawOldImage);
                }
                $validated['image'] = $request->file('image')->store('campaigns', 'public');
            }

            // Update slug jika judul berubah
            if ($request->title !== $campaign->title) {
                $validated['slug'] = Str::slug($request->title) . '-' . time();
            }

            $campaign->update($validated);

            return redirect()->route('admin.campaigns.index')
                ->with('success', 'Kampanye donasi berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error updating campaign: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal memperbarui kampanye: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // 7. DESTROY (Menghapus Kampanye)
    public function destroy($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);

            // Hapus file gambar dari storage
            $rawImage = $campaign->getRawOriginal('image');
            if ($rawImage && Storage::disk('public')->exists($rawImage)) {
                Storage::disk('public')->delete($rawImage);
            }

            // Hapus relasi yang bergantung untuk menjaga integritas data
            if (method_exists($campaign, 'distributionReports')) {
                $campaign->distributionReports()->delete();
            }
            if (method_exists($campaign, 'withdrawals')) {
                $campaign->withdrawals()->delete();
            }
            if (method_exists($campaign, 'donationTransactions')) {
                $campaign->donationTransactions()->delete();
            }
            if (method_exists($campaign, 'donations')) {
                $campaign->donations()->delete();
            }

            $campaign->delete();

            return redirect()->route('admin.campaigns.index')
                ->with('success', 'Kampanye donasi berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting campaign: ' . $e->getMessage());
            return redirect()->route('admin.campaigns.index')
                ->withErrors(['error' => 'Gagal menghapus kampanye: ' . $e->getMessage()]);
        }
    }
}