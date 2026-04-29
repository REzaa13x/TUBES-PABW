<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerCampaign;
use App\Models\Campaign; 
class CampaignController extends Controller
{
    // FUNGSI 1: Untuk Landing Page (Halaman Utama /relawan)
    // Hanya menampilkan 3-6 kampanye terbaru sebagai teaser
    public function landing()
    {
        $campaigns = VolunteerCampaign::where('status', 'Aktif')
            ->latest()
            ->take(3) // Ambil 3 saja untuk teaser di depan
            ->get();

        // Return ke file Landing Page
        return view('volunteer.index', compact('campaigns'));
    }

    // FUNGSI 2: Untuk Halaman List Kampanye (/campaigns) - Donation campaigns
    // Menampilkan SEMUA kampanye donasi dengan pagination
    public function index(Request $request)
    {
        $query = \App\Models\Campaign::where('status', 'verified')
            ->where('end_date', '>=', now());

        // Filter berdasarkan kategori jika disediakan
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search berdasarkan judul kampanye jika disediakan
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $campaigns = $query->latest()->paginate(9); // Gunakan pagination agar rapi

        // Ambil semua kategori unik untuk dropdown filter
        $kategoriOptions = \App\Models\Campaign::select('kategori')->distinct()->pluck('kategori');

        // Definisikan semua kategori yang tersedia
        $allKategori = ['Lingkungan', 'Kesehatan', 'Pendidikan', 'Sosial Kemanusiaan', 'Bencana Alam'];

        // Gabungkan kategori yang ada di database dengan semua kategori yang tersedia
        $kategoriOptions = collect(array_unique(array_merge($kategoriOptions->toArray(), $allKategori)))->sort();

        // Return ke file List Kampanye Donasi
        return view('campaigns.index', compact('campaigns', 'kategoriOptions'));
    }

    // FUNGSI 3: Untuk Halaman List Kampanye Relawan (/volunteer/campaigns)
    // Menampilkan SEMUA kampanye relawan dengan pagination
    public function volunteerIndex()
    {
        $campaigns = \App\Models\VolunteerCampaign::where('status', 'Aktif')
            ->where('tanggal_selesai', '>=', now())
            ->latest()
            ->paginate(9); // Gunakan pagination agar rapi

        // Return ke file List Kampanye Relawan
        return view('volunteer.campaigns.index', compact('campaigns'));
    }

    public function show($slug)
    {
        $campaign = VolunteerCampaign::where('slug', $slug)->firstOrFail();
        return view('volunteer.register', compact('campaign'));
    }

    // NEW: Show method for donation details with slug
    public function showDonation($slug)
    {
        $campaign = Campaign::where('slug', $slug)->first();

        if (!$campaign) {
            // Try to find by generating slug from title if the exact slug doesn't exist
            $campaign = Campaign::all()->first(function($c) use ($slug) {
                return \Illuminate\Support\Str::slug($c->title) === $slug;
            });
        }

        if (!$campaign) {
            abort(404, 'Campaign not found');
        }

        // Hitung jumlah donatur (jumlah transaksi donasi yang telah diverifikasi untuk kampanye ini)
        $donaturCount = \App\Models\DonationTransaction::where('campaign_id', $campaign->id)
            ->where('status', 'VERIFIED')
            ->count();

        // Hitung sisa hari dari tanggal akhir kampanye
        $endDate = \Carbon\Carbon::parse($campaign->end_date);
        $today = \Carbon\Carbon::today();
        $sisaHari = max(0, $today->diffInDays($endDate, false));

        // Get withdrawal information (fund distribution)
        $withdrawals = $campaign->withdrawals()->with('user')->get();
        $totalDistributed = $campaign->withdrawals()->sum('amount');
        $totalDonated = $campaign->current_amount ?? 0;
        $remainingFunds = max(0, $totalDonated - $totalDistributed);

        return view('donation-details', compact('campaign', 'donaturCount', 'sisaHari', 'withdrawals', 'totalDistributed', 'totalDonated', 'remainingFunds'));
    }

    // API method to get all campaigns
    public function apiIndex()
    {
        try {
            \Log::info('API Campaign Index called');

            $campaigns = Campaign::with('user')
                ->where('status', 'verified')
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Campaigns count: ' . $campaigns->count());

            // Log the first campaign's image to debug
            if ($campaigns->count() > 0) {
                $firstCampaign = $campaigns->first();
                \Log::info('First campaign image path: ' . ($firstCampaign->image ?? 'null'));
                \Log::info('First campaign image URL: ' . ($firstCampaign->getOriginal('image') ?? 'null'));
            }

            return response()->json([
                'message' => 'Campaigns retrieved successfully',
                'data' => \App\Http\Resources\CampaignResource::collection($campaigns)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in API Campaign Index: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());

            return response()->json([
                'message' => 'Error retrieving campaigns',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // FUNGSI: Riwayat Kampanye User
    public function history()
    {
        $campaigns = Campaign::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profiles.riwayat_kampanye', compact('campaigns'));
    }

    // FUNGSI: Simpan Kampanye Baru
    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'target' => 'required|numeric|min:10000',
                'deadline' => 'required|date|after:today',
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'kategori' => 'required|string',
                'lokasi' => 'required|string',
                'penerima' => 'required|string',
                'jenis_penerima' => 'required|string',
                'whatsapp' => 'required|string',
                'penyaluran' => 'required|string',
            ]);

            $imagePath = null;
            if ($request->hasFile('foto')) {
                $imagePath = $request->file('foto')->store('campaigns', 'public');
            }

            // --- PERBAIKAN: CEK DUPLIKASI (IDEMPOTENCY) ---
            $existing = Campaign::where('user_id', auth()->id())
                ->where('title', $request->judul)
                ->where('created_at', '>=', now()->subMinutes(1))
                ->first();

            if ($existing) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Kampanye sudah dibuat!',
                        'redirect' => route('profiles.campaign.history')
                    ]);
                }
                return redirect()->route('profiles.campaign.history')->with('success', 'Kampanye sudah berhasil dibuat!');
            }

            $campaign = Campaign::create([
                'title' => $request->judul,
                'description' => $request->deskripsi,
                'target_amount' => $request->target,
                'end_date' => $request->deadline,
                'image' => $imagePath,
                'kategori' => $request->kategori,
                'user_id' => auth()->id(),
                'status' => 'pending', 
                'yayasan' => $request->penerima, 
                'penyaluran' => $request->penyaluran,
                'lokasi' => $request->lokasi,
                'jenis_penerima' => $request->jenis_penerima,
                'whatsapp' => $request->whatsapp,
                'slug' => \Illuminate\Support\Str::slug($request->judul) . '-' . time(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kampanye berhasil dibuat dan sedang menunggu verifikasi!',
                    'redirect' => route('profiles.campaign.history')
                ]);
            }

            return redirect()->route('profiles.campaign.history')->with('success', 'Kampanye berhasil dibuat!');
        } catch (\Exception $e) {
            \Log::error('Campaign Store Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Terjadi kesalahan saat menyimpan kampanye.')->withInput();
        }
    }

    public function edit($id)
    {
        $campaign = Campaign::where('user_id', auth()->id())->findOrFail($id);

        if (!in_array($campaign->status, ['pending', 'rejected'])) {
            return redirect()->back()->with('error', 'Kampanye yang sudah diverifikasi tidak dapat diedit.');
        }

        return view('profiles.campaign_edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::where('user_id', auth()->id())->findOrFail($id);

        if (!in_array($campaign->status, ['pending', 'rejected'])) {
            return redirect()->back()->with('error', 'Kampanye yang sudah diverifikasi tidak dapat diubah.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'target' => 'required|numeric|min:10000',
            'deadline' => 'required|date|after:today',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategori' => 'required|string',
        ]);

        $data = [
            'title' => $request->judul,
            'description' => $request->deskripsi,
            'target_amount' => $request->target,
            'end_date' => $request->deadline,
            'kategori' => $request->kategori,
            'status' => 'pending', // Reset status to pending after edit
        ];

        if ($request->hasFile('foto')) {
            $data['image'] = $request->file('foto')->store('campaigns', 'public');
        }

        $campaign->update($data);

        return redirect()->route('profiles.campaign.history')->with('success', 'Kampanye berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::where('user_id', auth()->id())->findOrFail($id);
        $campaign->delete();

        return redirect()->route('profiles.campaign.history')->with('success', 'Kampanye berhasil dihapus!');
    }
}
