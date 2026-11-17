<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\VolunteerApplication;
use App\Models\VolunteerCampaign;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VolunteerApplicationController extends Controller
{
    /**
     * Get all volunteer applications for authenticated user
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $applications = VolunteerApplication::where('user_id', $user->id)
            ->with('campaign')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'message' => 'Volunteer applications retrieved successfully',
            'data' => $applications
        ]);
    }

    /**
     * Get specific volunteer application for authenticated user
     */
    public function show($id): JsonResponse
    {
        $user = Auth::user();
        $application = VolunteerApplication::where('id', $id)
            ->where('user_id', $user->id)
            ->with('campaign')
            ->first();

        if (!$application) {
            return response()->json([
                'message' => 'Volunteer application not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Volunteer application retrieved successfully',
            'data' => $application
        ]);
    }

    /**
     * Apply to volunteer campaign
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'volunteer_campaign_id' => 'required|exists:volunteer_campaigns,id',
            'alamat' => 'required|string|min:10',
            'posisi_dilamar' => 'required|string|max:255',
            'alasan_bergabung' => 'required|string|min:20',
            'cv' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Check if user has already applied to this campaign
        $existingApplication = VolunteerApplication::where('user_id', $user->id)
            ->where('volunteer_campaign_id', $request->volunteer_campaign_id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'Anda sudah mendaftar pada kampanye relawan ini. Periksa Riwayat Relawan.'
            ], 409);
        }

        // Check if campaign still has available quota
        $campaign = VolunteerCampaign::find($request->volunteer_campaign_id);
        if ($campaign && $campaign->kuota_terisi >= $campaign->kuota_total) {
            return response()->json([
                'message' => 'Kuota kampanye relawan ini sudah penuh.'
            ], 400);
        }

        $cvPath = $request->file('cv')->store('cv-uploads', 'public');
        try {
            $application = VolunteerApplication::create([
            'user_id' => $user->id,
            'volunteer_campaign_id' => $request->volunteer_campaign_id,
            'alamat' => $request->alamat,
            'posisi_dilamar' => $request->posisi_dilamar,
            'alasan_bergabung' => $request->alasan_bergabung,
            'cv_path' => $cvPath,
            'status' => 'pending' // Default status
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($cvPath);
            throw $e;
        }

        // Notifikasi ke Admin: Relawan baru mendaftar dari mobile
        try {
            $vcampaign = VolunteerCampaign::find($request->volunteer_campaign_id);
            AdminNotification::create([
                'title'               => '🤝 Pendaftar Relawan Baru (Mobile)',
                'message'             => $user->name . ' mendaftar sebagai relawan' .
                    ($vcampaign ? ' untuk kampanye "' . ($vcampaign->name ?? $vcampaign->judul ?? 'Unknown') . '"' : '') . ' melalui aplikasi mobile.',
                'category'            => 'relawan',
                'priority'            => 'medium',
                'icon'                => '🤝',
                'type'                => 'volunteer_registered',
                'action_label'        => 'Lihat Pendaftar',
                'action_url'          => route('admin.verifikasi-relawan.show', $application->id),
                'related_volunteer_id' => $application->id,
                'related_campaign_id'  => $request->volunteer_campaign_id,
                'related_user_id'      => $user->id,
                'metadata'             => [
                    'applicant_name' => $user->name,
                    'campaign_name'  => $vcampaign ? ($vcampaign->name ?? $vcampaign->judul ?? null) : null,
                    'posisi_dilamar' => $request->posisi_dilamar,
                    'source'         => 'mobile',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create admin notification for volunteer application: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Volunteer application submitted successfully',
            'data' => $application
        ], 201);
    }

    /**
     * Update volunteer application (only allowed for pending applications)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = Auth::user();

        $application = VolunteerApplication::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$application) {
            return response()->json([
                'message' => 'Volunteer application not found'
            ], 404);
        }

        // Only allow updates for pending applications
        if ($application->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot update application that is not in pending status'
            ], 400);
        }

        $request->validate([
            'motivation' => 'required|string',
            'skills' => 'required|string',
        ]);

        $application->update([
            'alasan_bergabung' => $request->motivation,
            'posisi_dilamar' => $request->skills,
        ]);

        return response()->json([
            'message' => 'Volunteer application updated successfully',
            'data' => $application
        ]);
    }

    /**
     * Cancel volunteer application (only allowed for pending applications)
     */
    public function destroy($id): JsonResponse
    {
        $user = Auth::user();

        $application = VolunteerApplication::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$application) {
            return response()->json([
                'message' => 'Volunteer application not found'
            ], 404);
        }

        // Only allow deletion for pending applications
        if ($application->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot cancel application that is not in pending status'
            ], 400);
        }

        $application->delete();

        return response()->json([
            'message' => 'Volunteer application cancelled successfully'
        ]);
    }
}
