<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerCertificate;
use App\Models\VolunteerApplication;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CertificateController extends Controller
{
    /**
     * Dashboard admin sertifikat.
     * Route: GET /admin/certificates
     */
    public function index(Request $request)
    {
        $query = VolunteerCertificate::with(['volunteer', 'campaign'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('volunteer_name', 'like', "%{$search}%")
                  ->orWhere('campaign_name', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%");
            });
        }

        $certificates = $query->paginate(15);

        // Stats
        $totalIssued     = VolunteerCertificate::count();
        $totalActive     = VolunteerCertificate::where('status', 'active')->count();
        $totalPending    = VolunteerCertificate::where('status', 'pending')->count();
        $totalRevoked    = VolunteerCertificate::where('status', 'revoked')->count();
        $thisMonth       = VolunteerCertificate::whereMonth('created_at', now()->month)->count();
        $uniqueVolunteers = VolunteerCertificate::distinct('volunteer_id')->count('volunteer_id');

        return view('admin.certificates.index', compact(
            'certificates',
            'totalIssued',
            'totalActive',
            'totalPending',
            'totalRevoked',
            'thisMonth',
            'uniqueVolunteers'
        ));
    }

    /**
     * Generate sertifikat manual dari lamaran yang sudah di-approve.
     * Route: POST /admin/certificates/generate/{applicationId}
     */
    public function generate($applicationId)
    {
        $application = VolunteerApplication::with(['user', 'campaign'])->findOrFail($applicationId);

        if ($application->status !== 'approved') {
            return back()->with('error', 'Sertifikat hanya bisa dibuat untuk lamaran yang sudah disetujui.');
        }

        $certificate = VolunteerCertificate::generateForApplication($application);

        // Notifikasi ke relawan
        try {
            $application->user->notify(new \App\Notifications\CertificateIssued($certificate));
        } catch (\Exception $e) {
            \Log::warning('Certificate notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Sertifikat berhasil dibuat untuk ' . $application->user->name . '.');
    }

    /**
     * Aktifkan sertifikat pending (setelah kampanye selesai).
     * Route: POST /admin/certificates/{id}/activate
     */
    public function activate($id)
    {
        $certificate = VolunteerCertificate::findOrFail($id);

        if ($certificate->status !== 'pending') {
            return back()->with('error', 'Hanya sertifikat berstatus Pending yang bisa diaktifkan.');
        }

        $certificate->activate();

        // Notifikasi ke relawan
        try {
            $certificate->volunteer->notify(new \App\Notifications\CertificateIssued($certificate));
        } catch (\Exception $e) {
            \Log::warning('Certificate notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Sertifikat ' . $certificate->certificate_number . ' berhasil diaktifkan. Relawan dapat mengunduhnya.');
    }

    /**
     * Batch activate all pending certificates for campaigns that have ended.
     * Route: POST /admin/certificates/activate-expired
     */
    public function activateExpired()
    {
        $now = Carbon::now();

        $pendingCerts = VolunteerCertificate::where('status', 'pending')
            ->where('end_date', '<', $now)
            ->with('volunteer')
            ->get();

        $count = 0;
        foreach ($pendingCerts as $cert) {
            $cert->update(['status' => 'active']);
            try {
                $cert->volunteer->notify(new \App\Notifications\CertificateIssued($cert));
            } catch (\Exception $e) {
                \Log::warning('Batch cert notification failed: ' . $e->getMessage());
            }
            $count++;
        }

        return back()->with('success', "{$count} sertifikat berhasil diaktifkan karena kampanye sudah selesai.");
    }

    /**
     * Cabut sertifikat.
     * Route: POST /admin/certificates/{id}/revoke
     */
    public function revoke($id)
    {
        $certificate = VolunteerCertificate::findOrFail($id);
        $certificate->revoke();

        // Admin notification
        try {
            AdminNotification::create([
                'title'    => '🚫 Sertifikat Dicabut',
                'message'  => 'Sertifikat ' . $certificate->certificate_number . ' milik ' . $certificate->volunteer_name . ' telah dicabut.',
                'category' => 'sistem',
                'priority' => 'medium',
                'icon'     => '🚫',
                'type'     => 'certificate_revoked',
                'metadata' => ['certificate_number' => $certificate->certificate_number],
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Sertifikat berhasil dicabut.');
    }

    /**
     * Download sertifikat sebagai PDF (admin).
     * Route: GET /admin/certificates/{id}/download
     */
    public function download($id)
    {
        $certificate = VolunteerCertificate::with('campaign')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.certificate', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $filename = 'Sertifikat-' . $certificate->certificate_number . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Detail sertifikat (admin).
     * Route: GET /admin/certificates/{id}
     */
    public function show($id)
    {
        $certificate = VolunteerCertificate::with(['volunteer', 'campaign', 'application'])->findOrFail($id);
        return view('admin.certificates.show', compact('certificate'));
    }
}
