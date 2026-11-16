<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VolunteerApplication;
use App\Models\VolunteerCampaign;
use App\Models\VolunteerCertificate;
use App\Models\AdminNotification;
use App\Notifications\VolunteerStatusChanged;
use App\Notifications\CertificateIssued;

class VolunteerVerificationController extends Controller
{

   public function index()
    {
        $allVolunteers = VolunteerApplication::with(['user', 'campaign'])
            ->latest()
            ->paginate(10);

        return view('admin.verifikasi-relawan.index', compact('allVolunteers'));
    }

  // 1. MENAMPILKAN DETAIL
    public function show($id)
    {
        $volunteer = VolunteerApplication::with(['user', 'campaign'])->findOrFail($id);
        return view('admin.verifikasi-relawan.show', compact('volunteer'));
    }

    // 2. PROSES TERIMA / TOLAK
    public function update(Request $request, $id)
    {
        // 1. Ambil Data Aplikasi
        $application = \App\Models\VolunteerApplication::findOrFail($id);

        // 2. Definisikan campaign dari relasi aplikasi
        $campaign = $application->campaign;

        // 3. Simpan status
        $application->status = $request->status;
        $application->save();

        // 4. Logika Kuota (Hanya jika Approved)
        if ($request->status == 'approved') {
            $campaign->increment('kuota_terisi');

            // ─── AUTO-GENERATE SERTIFIKAT ─────────────────────────────────
            try {
                $certificate = VolunteerCertificate::generateForApplication($application);

                // Notifikasi relawan: sertifikat sudah dibuat
                // (Aktif jika kampanye sudah selesai, pending jika belum)
                if ($certificate->status === 'active') {
                    $application->user->notify(new CertificateIssued($certificate));
                }

                // Admin notification
                AdminNotification::create([
                    'title'               => '🎓 Sertifikat Relawan Dibuat',
                    'message'             => 'Sertifikat ' . $certificate->certificate_number .
                        ' berhasil dibuat untuk ' . $application->user->name .
                        ' (' . $campaign->judul . ').' .
                        ($certificate->status === 'pending' ? ' Menunggu kampanye selesai untuk diaktifkan.' : ' Sudah aktif.'),
                    'category'            => 'relawan',
                    'priority'            => 'low',
                    'icon'                => '🎓',
                    'type'                => 'certificate_created',
                    'action_label'        => 'Lihat Sertifikat',
                    'action_url'          => route('admin.certificates.show', $certificate->id),
                    'related_volunteer_id' => $application->id,
                    'related_user_id'      => $application->user_id,
                    'metadata'             => [
                        'certificate_number' => $certificate->certificate_number,
                        'volunteer_name'     => $application->user->name,
                        'campaign_name'      => $campaign->judul,
                    ],
                ]);
            } catch (\Exception $certEx) {
                \Log::error('Auto-generate certificate failed: ' . $certEx->getMessage());
            }
        }

        // 5. Kirim Notifikasi status ke relawan
        $user = $application->user;
        if ($user) {
            $user->notify(new VolunteerStatusChanged(
                $campaign->judul,
                $request->status,
                $campaign->slug
            ));
        }

        $message = $request->status == 'approved'
            ? 'Relawan diterima! Sertifikat otomatis dibuat.'
            : 'Lamaran ditolak.';
        return redirect()->route('admin.verifikasi-relawan.index')->with('success', $message);
    }
}