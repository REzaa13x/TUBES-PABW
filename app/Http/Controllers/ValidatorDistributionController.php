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

        if (strtolower($campaign->status) === 'pending') {
            // Step 1: Halaman khusus untuk verifikasi kampanye baru yang terfokus
            return view('validator.verify-pending', compact('campaign', 'token'));
        }

        // Step 2: Halaman daftar riwayat seluruh kampanye yang dikelola/diverifikasi oleh validator ini
        $myCampaigns = [];
        if ($campaign->validator_phone) {
            $myCampaigns = Campaign::where('validator_phone', $campaign->validator_phone)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $myCampaigns = [$campaign];
        }

        return view('validator.my-campaigns', compact('campaign', 'myCampaigns', 'token'));
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
            
        // Pengeluaran 2: Laporan Penyaluran oleh Validator
        $distributions = $campaign->distributionReports()
            ->where('status', 'verified')
            ->latest()
            ->get();

        return view('validator.campaign-detail', array_merge($data, compact(
            'campaign', 
            'token', 
            'donations', 
            'withdrawals', 
            'distributions'
        )));
    }

    public function upload($token)
    {
        // Validator hanya berperan sebagai pengawas transparansi, bukan pelapor penyaluran
        return redirect()->route('validator.campaign', $token)
            ->with('info', 'Validator hanya dapat memantau transparansi aliran dana. Form penyaluran dikelola oleh admin sistem.');
    }

    public function store(Request $request, $token)
    {
        // Validator tidak diperkenankan mengajukan laporan penyaluran
        return redirect()->route('validator.campaign', $token)
            ->with('info', 'Validator hanya dapat memantau transparansi aliran dana. Form penyaluran dikelola oleh admin sistem.');
    }

    public function history($token)
    {
        $campaign = $this->getCampaign($token);
        
        // Tampilkan semua kampanye yang diawasi validator ini (berdasarkan nomor HP validator)
        $myCampaigns = collect();
        if ($campaign->validator_phone) {
            $myCampaigns = Campaign::where('validator_phone', $campaign->validator_phone)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $myCampaigns = collect([$campaign]);
        }
        
        return view('validator.history', compact('campaign', 'myCampaigns', 'token'));
    }

    public function status($token)
    {
        $campaign = $this->getCampaign($token);
        $reports = $campaign->distributionReports()->latest()->get();
        return view('validator.status', compact('campaign', 'reports', 'token'));
    }
}
