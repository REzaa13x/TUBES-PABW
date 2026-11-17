<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VolunteerCertificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class CertificateController extends Controller
{
    /**
     * List sertifikat milik user yang terautentikasi.
     * GET /api/v1/certificates
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $certificates = VolunteerCertificate::where('volunteer_id', $user->id)
            ->with('campaign')
            ->latest()
            ->get()
            ->map(fn($c) => $this->formatCertificate($c));

        return response()->json([
            'message' => 'Certificates retrieved successfully',
            'data'    => $certificates,
            'meta'    => [
                'total'         => $certificates->count(),
                'total_campaigns' => $certificates->pluck('campaign_id')->filter()->unique()->count(),
                'total_active'  => $certificates->where('status', 'active')->count(),
                'total_hours'   => $certificates->where('status', 'active')->sum('total_volunteer_hours'),
                'volunteer_level' => VolunteerCertificate::determineLevel(
                    $certificates->where('status', 'active')->sum('total_volunteer_hours')
                ),
            ],
        ]);
    }

    /**
     * Detail sertifikat.
     * GET /api/v1/certificates/{id}
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $certificate = VolunteerCertificate::where('volunteer_id', $user->id)
            ->with(['campaign'])
            ->find($id);

        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found'], 404);
        }

        return response()->json([
            'message' => 'Certificate retrieved successfully',
            'data'    => $this->formatCertificate($certificate),
        ]);
    }

    /**
     * Verifikasi sertifikat publik (tanpa auth).
     * GET /api/v1/certificate/verify/{certificateNumber}
     */
    public function verify($certificateNumber): JsonResponse
    {
        $certificate = VolunteerCertificate::where('certificate_number', $certificateNumber)
            ->with(['campaign', 'volunteer'])
            ->first();

        if (!$certificate) {
            return response()->json([
                'is_valid'  => false,
                'message'   => 'Sertifikat tidak ditemukan.',
                'data'      => null,
            ]);
        }

        $isValid = $certificate->status === 'active';

        return response()->json([
            'is_valid'  => $isValid,
            'message'   => $isValid ? 'Sertifikat valid.' : 'Sertifikat tidak aktif (' . $certificate->status_label . ').',
            'data'      => [
                'certificate_number' => $certificate->certificate_number,
                'volunteer_name'     => $certificate->volunteer_name,
                'campaign_name'      => $certificate->campaign_name,
                'category'           => $certificate->category,
                'location'           => $certificate->location,
                'issue_date'         => optional($certificate->issue_date)->format('d M Y'),
                'volunteer_level'    => $certificate->volunteer_level,
                'total_volunteer_hours' => $certificate->total_volunteer_hours,
                'organizer_name'     => $certificate->organizer_name,
                'status'             => $certificate->status,
                'status_label'       => $certificate->status_label,
            ],
        ]);
    }

    /**
     * URL download PDF (hanya untuk sertifikat aktif).
     * GET /api/v1/certificates/{id}/download-url
     */
    public function downloadUrl(Request $request, $id): JsonResponse
    {
        $user        = $request->user();
        $certificate = VolunteerCertificate::where('volunteer_id', $user->id)->find($id);

        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found'], 404);
        }

        if (!$certificate->is_downloadable) {
            return response()->json([
                'message' => 'Sertifikat belum dapat diunduh. Kampanye harus selesai terlebih dahulu.',
                'status'  => $certificate->status,
            ], 403);
        }

        $relative = URL::temporarySignedRoute(
            'api.certificates.signed-download', now()->addMinutes(10), ['id' => $certificate->id], absolute: false
        );
        return response()->json([
            'expires_at' => now()->addMinutes(10)->toISOString(),
            'message'      => 'Download URL generated',
            'download_url' => url($relative),
            'certificate_number' => $certificate->certificate_number,
        ]);
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    private function formatCertificate(VolunteerCertificate $cert): array
    {
        return [
            'id'                    => $cert->id,
            'certificate_id'        => $cert->certificate_id,
            'certificate_number'    => $cert->certificate_number,
            'volunteer_id'          => $cert->volunteer_id,
            'volunteer_name'        => $cert->volunteer_name,
            'campaign_id'           => $cert->campaign_id,
            'campaign_name'         => $cert->campaign_name,
            'category'              => $cert->category,
            'location'              => $cert->location,
            'start_date'            => optional($cert->start_date)->toDateString(),
            'end_date'              => optional($cert->end_date)->toDateString(),
            'issue_date'            => optional($cert->issue_date)->toDateString(),
            'volunteer_level'       => $cert->volunteer_level,
            'total_volunteer_hours' => $cert->total_volunteer_hours,
            'organizer_name'        => $cert->organizer_name,
            'status'                => $cert->status,
            'status_label'          => $cert->status_label,
            'is_downloadable'       => $cert->is_downloadable,
            'certificate_file'      => $cert->certificate_file,
            'qr_code'               => $cert->qr_url,
            'verify_url'            => $cert->verify_url,
            'download_url'          => null,
            'created_at'            => $cert->created_at?->toISOString(),
        ];
    }
}
