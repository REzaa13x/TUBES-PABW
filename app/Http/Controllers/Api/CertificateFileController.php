<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VolunteerCertificate;
use App\Services\CertificatePdfService;
use Illuminate\Http\Request;

class CertificateFileController extends Controller
{
    public function download(Request $request, int $id, CertificatePdfService $pdf)
    {
        $certificate = VolunteerCertificate::where('volunteer_id', $request->user()->id)->findOrFail($id);
        return $pdf->download($certificate);
    }

    // Access is granted only by the expiring signed URL middleware, not a browser session.
    public function signedDownload(int $id, CertificatePdfService $pdf)
    {
        return $pdf->download(VolunteerCertificate::findOrFail($id));
    }

    public function qr(string $certificateNumber)
    {
        $certificate = VolunteerCertificate::where('certificate_number', $certificateNumber)->firstOrFail();
        return response($certificate->qr_png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=300',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
