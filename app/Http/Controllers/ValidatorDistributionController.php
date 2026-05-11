<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\DistributionReport;
use Illuminate\Http\Request;
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

        $data = $this->getValidatorData($campaign);
        $totalPenyaluran = $campaign->distributionReports()->where('status', 'verified')->sum('amount');
        $reports = $campaign->distributionReports()->latest()->get();

        return view('validator.dashboard', array_merge($data, compact('campaign', 'totalPenyaluran', 'reports', 'token')));
    }

    // Fitur Baru: Verifikasi Kampanye via Portal (Tanpa Login)
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
        return view('validator.campaign-detail', array_merge($data, compact('campaign', 'token')));
    }

    public function upload($token)
    {
        $campaign = $this->getCampaign($token);
        
        if (strtolower($campaign->status) === 'pending') {
            return redirect()->route('validator.dashboard', $token)->with('error', 'Kampanye harus disetujui terlebih dahulu sebelum melaporkan penyaluran.');
        }

        $data = $this->getValidatorData($campaign);
        return view('validator.upload-proof', array_merge($data, compact('campaign', 'token')));
    }

    public function store(Request $request, $token)
    {
        $campaign = $this->getCampaign($token);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
            'proof_image' => 'required|image|max:5120',
        ]);

        // HITUNG SALDO ASLI sebelum SIMPAN LAPORAN
        $usedManual = $campaign->withdrawals()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount'); 
        
        $usedValidator = $campaign->distributionReports()
            ->where('status', 'verified')
            ->sum('amount');

        $actualBalance = $campaign->current_amount - ($usedManual + $usedValidator);

        if ($request->amount > $actualBalance) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Saldo tidak mencukupi! Dana yang tersedia untuk disalurkan saat ini adalah Rp ' . number_format($actualBalance, 0, ',', '.'));
        }

        $path = $request->file('proof_image')->store('distribution-proofs', 'public');

        DistributionReport::create([
            'campaign_id' => $campaign->id,
            'amount' => $request->amount,
            'description' => $request->description,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('validator.history', $token)->with('success', 'Laporan penyaluran berhasil dikirim.');
    }

    public function history($token)
    {
        $campaign = $this->getCampaign($token);
        $reports = $campaign->distributionReports()->latest()->get();
        return view('validator.history', compact('campaign', 'reports', 'token'));
    }

    public function status($token)
    {
        $campaign = $this->getCampaign($token);
        $reports = $campaign->distributionReports()->latest()->get();
        return view('validator.status', compact('campaign', 'reports', 'token'));
    }
}
