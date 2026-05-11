<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\DistributionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DistributionController extends Controller
{
    public function index()
    {
        $reports = DistributionReport::with('campaign')->latest()->paginate(10);
        return view('admin.distribution.index', compact('reports'));
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
