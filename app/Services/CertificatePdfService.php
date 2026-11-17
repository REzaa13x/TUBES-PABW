<?php

namespace App\Services;

use App\Models\VolunteerCertificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificatePdfService
{
    public function download(VolunteerCertificate $certificate)
    {
        abort_unless($certificate->is_downloadable, 403,
            $certificate->status === 'revoked'
                ? 'Sertifikat telah dicabut dan tidak dapat diunduh.'
                : 'Sertifikat belum dapat diunduh. Kegiatan belum selesai.');

        $filename = 'Sertifikat-'.preg_replace('/[^A-Za-z0-9_-]/', '-', $certificate->certificate_number).'.pdf';
        // Use exactly the same template for website and mobile. QR is embedded locally.
        return Pdf::loadView('pdf.certificate', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false])
            ->download($filename)
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}
