<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\DistributionReport;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DistributionController extends Controller
{
    public function index(Request $request)
    {
        $query = DistributionReport::with('campaign')->latest();

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('recipient', 'like', "%{$search}%")
                  ->orWhereHas('campaign', function ($cq) use ($search) {
                      $cq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->paginate(10)->appends($request->query());

        $stats = [
            'total_reports' => DistributionReport::count(),
            'total_amount' => DistributionReport::where('status', 'verified')->sum('amount'),
            'pending_reports' => DistributionReport::where('status', 'pending')->count(),
            'verified_reports' => DistributionReport::where('status', 'verified')->count(),
        ];

        $campaigns = Campaign::orderBy('title', 'asc')->get();

        return view('admin.distribution.index', compact('reports', 'stats', 'campaigns'));
    }

    public function create(Request $request)
    {
        $campaigns = Campaign::orderBy('title', 'asc')->get();
        $selectedCampaign = null;

        if ($request->filled('campaign_id')) {
            $selectedCampaign = Campaign::find($request->campaign_id);
        }

        return view('admin.distribution.create', compact('campaigns', 'selectedCampaign'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:1',
            'recipient' => 'required|string|max:255',
            'description' => 'required|string',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'nullable|in:verified,pending',
            'admin_note' => 'nullable|string',
        ]);

        $campaign = Campaign::findOrFail($request->campaign_id);

        // Upload Foto Dokumentasi Bukti Penyaluran
        $image = $request->file('proof_image');
        $imageName = 'dist_' . time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
        
        $destinationPath = public_path('uploads/distributions');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $image->move($destinationPath, $imageName);
        $imagePath = 'uploads/distributions/' . $imageName;

        $status = $request->input('status', 'verified');

        $report = DistributionReport::create([
            'campaign_id' => $campaign->id,
            'amount' => $request->amount,
            'recipient' => $request->recipient,
            'description' => $request->description,
            'proof_image' => $imagePath,
            'status' => $status,
            'admin_note' => $request->input('admin_note', 'Laporan penyaluran resmi tercatat di sistem oleh Admin DonGiv.'),
        ]);

        // Catat Admin Notification
        AdminNotification::create([
            'title' => 'Laporan Penyaluran Kampanye Dibuat',
            'message' => 'Laporan penyaluran Rp ' . number_format($request->amount, 0, ',', '.') . ' untuk penerima "' . $request->recipient . '" (' . $campaign->title . ') berhasil diterbitkan.',
            'category' => 'laporan',
            'priority' => 'medium',
            'icon' => '📑',
            'action_label' => 'Lihat Laporan',
            'action_url' => route('admin.distribution.show', $report->id),
            'related_campaign_id' => $campaign->id,
            'is_read' => false,
        ]);

        return redirect()->route('admin.distribution.index')
                         ->with('success', 'Laporan penyaluran kampanye berhasil dibuat! Validator kini dapat melihat hasil laporannya secara langsung.');
    }

    public function show($id)
    {
        $report = DistributionReport::with('campaign')->findOrFail($id);
        return view('admin.distribution.show', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $report = DistributionReport::findOrFail($id);
        $campaign = $report->campaign;

        $request->validate([
            'status' => 'required|in:verified,rejected',
            'admin_note' => 'nullable|string',
        ]);

        if ($request->status === 'verified') {
            $usedManual = $campaign->withdrawals()
                ->whereIn('status', ['approved', 'completed'])
                ->sum('amount'); 
            
            $usedValidator = $campaign->distributionReports()
                ->where('status', 'verified')
                ->where('id', '!=', $report->id)
                ->sum('amount');

            $actualBalance = $campaign->current_amount - ($usedManual + $usedValidator);

            if ($report->amount > $actualBalance) {
                return back()->withErrors(['status' => 'Gagal! Saldo kampanye tidak mencukupi untuk menyetujui laporan ini. Sisa saldo: Rp ' . number_format($actualBalance, 0, ',', '.')]);
            }
        }

        $report->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->route('admin.distribution.index')->with('success', 'Status laporan penyaluran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $report = DistributionReport::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.distribution.index')->with('success', 'Laporan penyaluran berhasil dihapus.');
    }

    public function generateLink(Request $request, $campaign_id)
    {
        $campaign = Campaign::findOrFail($campaign_id);
        $token = Str::uuid()->toString();
        
        $validatorName = 'Validator Tamu';
        $validatorPhone = null;

        if ($request->filled('validator_id')) {
            $contact = \App\Models\ValidatorContact::find($request->validator_id);
            if ($contact) {
                $validatorName = $contact->name;
                $validatorPhone = $contact->phone;
            }
        }

        $campaign->update([
            'distribution_token' => $token,
            'validator_name' => $validatorName,
            'validator_phone' => $validatorPhone, 
        ]);

        $link = route('validator.dashboard', $token);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'link' => $link,
                'validator_name' => $validatorName,
                'validator_phone' => $validatorPhone
            ]);
        }
        
        return back()->with('success', 'Link validator berhasil dibuat untuk ' . $validatorName . ': ' . $link);
    }
}
