<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\DistributionReport;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ValidatorDistributionController extends Controller
{
    private function getCampaign($token)
    {
        return Campaign::where('distribution_token', $token)
            ->orWhere('verification_token', $token) // Dukung token lama jika ada
            ->firstOrFail();
    }

    private function getValidatorData($campaign)
    {
        $activeTasks = [];
        $completedTasks = [];
        
        if ($campaign->validator_phone) {
            $allTasks = Campaign::where('validator_phone', $campaign->validator_phone)
                ->where('id', '!=', $campaign->id)
                ->with('distributionReports')
                ->get();
            
            foreach($allTasks as $task) {
                if ($task->status == 'verified' && $task->current_amount >= $task->target_amount) {
                    $completedTasks[] = $task;
                } else {
                    $activeTasks[] = $task;
                }
            }
        }

        return compact('activeTasks', 'completedTasks');
    }

    public function dashboard($token)
    {
        $campaign = $this->getCampaign($token);
        if (!$campaign) return redirect()->route('home')->with('error', 'Token tidak valid.');

        if (strtolower($campaign->status) === 'pending') {
            // Step 1: Halaman khusus untuk verifikasi kampanye baru yang terfokus
            return view('validator.verify-pending', compact('campaign', 'token'));
        }

        // Step 2: Halaman daftar riwayat seluruh kampanye yang dikelola/diverifikasi oleh validator ini
        $myCampaigns = [];
        if ($campaign->validator_phone) {
            $myCampaigns = Campaign::where('validator_phone', $campaign->validator_phone)
                ->with('distributionReports')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $myCampaigns = [$campaign->load('distributionReports')];
        }

        return view('validator.my-campaigns', compact('campaign', 'myCampaigns', 'token'));
    }

    // Fitur: Verifikasi Kampanye via Portal (Tanpa Login)
    public function verifyCampaign($token)
    {
        $campaign = $this->getCampaign($token);
        
        if (strtolower($campaign->status) !== 'pending') {
            return redirect()->route('validator.dashboard', $token)->with('info', 'Kampanye ini sudah terverifikasi.');
        }

        $campaign->update([
            'status' => 'verified',
            'verified_at' => Carbon::now(),
            'validator_name' => 'Validator Lapangan (Via Link)',
        ]);

        return redirect()->route('validator.dashboard', $token)->with('success', 'Kampanye berhasil disetujui dan kini aktif menerima donasi!');
    }

    public function campaign($token)
    {
        $campaign = $this->getCampaign($token);
        if (!$campaign) return redirect()->route('home')->with('error', 'Token tidak valid.');
        
        $data = $this->getValidatorData($campaign);
        
        // Pemasukan (Donasi Terverifikasi)
        $donations = $campaign->donationTransactions()
            ->where('status', 'VERIFIED')
            ->latest()
            ->get();
            
        // Pengeluaran 1: Penarikan Dana Manual oleh Pembuat
        $withdrawals = $campaign->withdrawals()
            ->whereIn('status', ['approved', 'completed'])
            ->latest()
            ->get();
            
        // Pengeluaran 2: Seluruh Laporan Penyaluran Kampanye (Hasil Kegiatan)
        $distributions = $campaign->distributionReports()
            ->latest()
            ->get();

        $totalPenyaluran = $distributions->where('status', 'verified')->sum('amount');
        $totalPenarikan = $withdrawals->sum('amount');
        $sisaSaldo = max(0, $campaign->current_amount - ($totalPenyaluran + $totalPenarikan));

        return view('validator.campaign-detail', array_merge($data, compact(
            'campaign', 
            'token', 
            'donations', 
            'withdrawals', 
            'distributions',
            'totalPenyaluran',
            'totalPenarikan',
            'sisaSaldo'
        )));
    }

    public function upload($token)
    {
        $campaign = $this->getCampaign($token);
        return view('validator.upload-proof', compact('campaign', 'token'));
    }

    public function store(Request $request, $token)
    {
        $campaign = $this->getCampaign($token);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'recipient' => 'required|string|max:255',
            'description' => 'required|string',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $image = $request->file('proof_image');
        $imageName = 'dist_val_' . time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
        
        $destinationPath = public_path('uploads/distributions');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $image->move($destinationPath, $imageName);
        $imagePath = 'uploads/distributions/' . $imageName;

        $report = DistributionReport::create([
            'campaign_id' => $campaign->id,
            'amount' => $request->amount,
            'recipient' => $request->recipient,
            'description' => $request->description,
            'proof_image' => $imagePath,
            'status' => 'verified', // Terverifikasi langsung karena dilaporkan oleh validator resmi
            'admin_note' => 'Laporan penyaluran diunggah dan divalidasi langsung oleh Validator Lapangan.',
        ]);

        // Notifikasi Admin
        AdminNotification::create([
            'title' => 'Laporan Penyaluran dari Validator',
            'message' => 'Validator mengunggah laporan penyaluran Rp ' . number_format($request->amount, 0, ',', '.') . ' untuk ' . $campaign->title . '.',
            'category' => 'relawan',
            'priority' => 'medium',
            'icon' => '📑',
            'action_label' => 'Periksa Laporan',
            'action_url' => route('admin.distribution.show', $report->id),
            'related_campaign_id' => $campaign->id,
            'is_read' => false,
        ]);

        return redirect()->route('validator.campaign', $token)
            ->with('success', 'Laporan penyaluran kampanye berhasil disimpan dan dicantumkan di portal!');
    }

    public function history($token)
    {
        $campaign = $this->getCampaign($token);
        
        $myCampaigns = collect();
        if ($campaign->validator_phone) {
            $myCampaigns = Campaign::where('validator_phone', $campaign->validator_phone)
                ->with('distributionReports')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $myCampaigns = collect([$campaign->load('distributionReports')]);
        }
        
        return view('validator.history', compact('campaign', 'myCampaigns', 'token'));
    }

    public function status($token)
    {
        $campaign = $this->getCampaign($token);
        $reports = $campaign->distributionReports()->latest()->get();
        $totalPenyaluran = $reports->where('status', 'verified')->sum('amount');
        return view('validator.status', compact('campaign', 'reports', 'token', 'totalPenyaluran'));
    }
}
