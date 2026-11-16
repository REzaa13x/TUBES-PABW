<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VolunteerCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Daftar sertifikat milik user yang sedang login.
     * Route: GET /volunteer/certificates
     */
    public function index()
    {
        $user = Auth::user();

        $certificates = VolunteerCertificate::where('volunteer_id', $user->id)
            ->with('campaign')
            ->latest()
            ->get();

        // Stats
        $totalCertificates  = $certificates->count();
        $totalActive        = $certificates->where('status', 'active')->count();
        $totalHours         = $certificates->where('status', 'active')->sum('total_volunteer_hours');
        $totalCampaigns     = $certificates->unique('campaign_id')->count();

        // Level relawan berdasarkan total jam aktif
        $level = VolunteerCertificate::determineLevel($totalHours);

        $levelIcon = match ($level) {
            'Relawan Ahli'   => '🏆',
            'Relawan Senior' => '🥇',
            'Relawan Aktif'  => '🥈',
            default          => '🌱',
        };

        return view('certificates.index', compact(
            'certificates',
            'totalCertificates',
            'totalActive',
            'totalHours',
            'totalCampaigns',
            'level',
            'levelIcon'
        ));
    }

    /**
     * Preview sertifikat (web).
     * Route: GET /volunteer/certificates/{id}
     */
    public function show($id)
    {
        $user        = Auth::user();
        $certificate = VolunteerCertificate::where('volunteer_id', $user->id)
            ->with(['campaign', 'application'])
            ->findOrFail($id);

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Download sertifikat sebagai PDF.
     * Route: GET /volunteer/certificates/{id}/download
     */
    public function download($id)
    {
        $user        = Auth::user();
        $certificate = VolunteerCertificate::where('volunteer_id', $user->id)
            ->with(['campaign'])
            ->findOrFail($id);

        if (!$certificate->is_downloadable) {
            return back()->with('error', 'Sertifikat belum dapat diunduh. Kampanye harus selesai terlebih dahulu.');
        }

        $pdf = Pdf::loadView('pdf.certificate', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $filename = 'Sertifikat-' . $certificate->certificate_number . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Halaman verifikasi publik sertifikat.
     * Route: GET /certificate/verify/{certificateNumber}
     */
    public function verify($certificateNumber)
    {
        $certificate = VolunteerCertificate::where('certificate_number', $certificateNumber)
            ->with(['campaign', 'volunteer'])
            ->first();

        $isValid = $certificate && $certificate->status === 'active';

        return view('certificates.verify', compact('certificate', 'isValid'));
    }
}
