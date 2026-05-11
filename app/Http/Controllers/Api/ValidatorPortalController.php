<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\DistributionReport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ValidatorPortalController extends Controller
{
    private function getCampaign($token)
    {
        return Campaign::where('distribution_token', $token)
            ->orWhere('verification_token', $token)
            ->first();
    }

    public function dashboard($token)
    {
        $campaign = $this->getCampaign($token);
        if (!$campaign) {
            return response()->json(['message' => 'Token tidak valid'], 404);
        }

        $totalPenyaluran = $campaign->distributionReports()->where('status', 'verified')->sum('amount');
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'campaign' => [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'status' => $campaign->status,
                    'target_amount' => $campaign->target_amount,
                    'current_amount' => $campaign->current_amount,
                    'total_disbursed' => $totalPenyaluran,
                    'image' => $campaign->image,
                    'kategori' => $campaign->kategori,
                    'lokasi' => $campaign->lokasi,
                    'jenis_penerima' => $campaign->jenis_penerima,
                    'whatsapp' => $campaign->whatsapp,
                ],
                'latest_reports' => $campaign->distributionReports()->latest()->take(5)->get()
            ]
        ]);
    }

    public function verifyCampaign($token)
    {
        $campaign = $this->getCampaign($token);
        if (!$campaign) return response()->json(['message' => 'Token tidak valid'], 404);
        
        if (strtolower($campaign->status) !== 'pending') {
            return response()->json(['message' => 'Kampanye sudah terverifikasi'], 400);
        }

        // Gunakan nama yang sudah ditugaskan admin atau default jika tidak ada
        $finalValidatorName = $campaign->validator_name ?: 'Validator Lapangan';

        $campaign->update([
            'status' => 'verified',
            'verified_at' => Carbon::now(),
            'validator_name' => $finalValidatorName,
        ]);

        return response()->json(['message' => 'Kampanye berhasil disetujui']);
    }

    public function storeReport(Request $request, $token)
    {
        $campaign = $this->getCampaign($token);
        if (!$campaign) return response()->json(['message' => 'Token tidak valid'], 404);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
            'proof_image' => 'required|image|max:5120',
        ]);

        $path = $request->file('proof_image')->store('distribution-proofs', 'public');

        $report = DistributionReport::create([
            'campaign_id' => $campaign->id,
            'amount' => $request->amount,
            'description' => $request->description,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Laporan penyaluran berhasil dikirim',
            'data' => $report
        ]);
    }

    public function history($token)
    {
        $campaign = $this->getCampaign($token);
        if (!$campaign) return response()->json(['message' => 'Token tidak valid'], 404);

        $reports = $campaign->distributionReports()->latest()->get();
        return response()->json(['status' => 'success', 'data' => $reports]);
    }
}
