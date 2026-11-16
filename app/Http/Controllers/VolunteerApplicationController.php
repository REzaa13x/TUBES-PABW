<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerCampaign;
use App\Models\VolunteerApplication;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;

class VolunteerApplicationController extends Controller
{
    public function create($slug)
    {
        $campaign = VolunteerCampaign::where('slug', $slug)->firstOrFail();

        // Cek existing (UX agar user tidak capek isi form kalau sudah daftar)
        $existing = VolunteerApplication::where('user_id', Auth::id())
            ->where('volunteer_campaign_id', $campaign->id)
            ->first();

        if ($existing) {
            // Redirect dengan pesan error
            return redirect()->route('volunteer.campaigns.index') // Saran: Redirect ke index saja agar lebih rapi
                ->with('error', 'Anda sudah terdaftar di kampanye ini.');
        }

        return view('volunteer.register', compact('campaign'));
    }

    public function store(Request $request, $slug)
    {
        $campaign = VolunteerCampaign::where('slug', $slug)->firstOrFail();

        // 1. CEK ULANG APAKAH SUDAH DAFTAR (PENTING UNTUK KEAMANAN DATA)
        $existing = VolunteerApplication::where('user_id', Auth::id())
            ->where('volunteer_campaign_id', $campaign->id)
            ->exists();

        if ($existing) {
            return redirect()->route('volunteer.campaigns.index')
                ->with('error', 'Gagal! Anda sudah terdaftar sebelumnya.');
        }

        // 2. Validasi
        $request->validate([
            'alamat' => 'required|string|min:10',
            'posisi_dilamar' => 'required|string',
            'alasan_bergabung' => 'required|string|min:20',
            'cv' => 'required|file|mimes:pdf|max:2048',
        ]);

        // 3. Upload CV
        try {
            $cvPath = $request->file('cv')->store('cv-uploads', 'public');

            $application = VolunteerApplication::create([
                'user_id' => Auth::id(),
                'volunteer_campaign_id' => $campaign->id,
                'alamat' => $request->alamat,
                'posisi_dilamar' => $request->posisi_dilamar,
                'alasan_bergabung' => $request->alasan_bergabung,
                'cv_path' => $cvPath,
                'status' => 'pending' // Default pending
            ]);

            // Notifikasi ke Admin: Relawan baru mendaftar
            try {
                $user = Auth::user();
                AdminNotification::create([
                    'title'               => '🤝 Pendaftar Relawan Baru',
                    'message'             => $user->name . ' mendaftar sebagai relawan untuk kampanye "' . $campaign->name . '" pada posisi ' . $request->posisi_dilamar . '.',
                    'category'            => 'relawan',
                    'priority'            => 'medium',
                    'icon'                => '🤝',
                    'type'                => 'volunteer_registered',
                    'action_label'        => 'Lihat Pendaftar',
                    'action_url'          => route('admin.verifikasi-relawan.show', $application->id),
                    'related_volunteer_id' => $application->id,
                    'related_campaign_id'  => $campaign->id,
                    'related_user_id'      => $user->id,
                    'metadata'             => [
                        'applicant_name'  => $user->name,
                        'campaign_name'   => $campaign->name,
                        'posisi_dilamar'  => $request->posisi_dilamar,
                    ],
                ]);
            } catch (\Exception $notifEx) {
                \Log::warning('Admin notification for volunteer app failed: ' . $notifEx->getMessage());
            }

            // 4. Redirect Sukses
            return redirect()->route('volunteer.campaigns.index')
                ->with('success', 'Formulir Anda telah terkirim! Tim kami akan segera melakukan verifikasi.');

        } catch (\Exception $e) {
            // Handle jika upload gagal atau DB error
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function checkStatus($slug)
    {
        $campaign = VolunteerCampaign::where('slug', $slug)->firstOrFail();

        $application = VolunteerApplication::where('user_id', Auth::id())
            ->where('volunteer_campaign_id', $campaign->id)
            ->firstOrFail();

        return view('volunteer.status', [
            'campaign'    => $campaign,
            'application' => $application,
            'status'      => $application->status
        ]);
    }
}