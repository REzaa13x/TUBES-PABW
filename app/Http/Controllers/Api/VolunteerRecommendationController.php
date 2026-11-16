<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VolunteerCampaign;
use App\Models\VolunteerCategoryPreference;
use App\Models\RecommendationNotification;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerRecommendationController extends Controller
{
    /**
     * Dapatkan kategori yang tersedia
     */
    public function getAvailableCategories()
    {
        $categories = ['Pendidikan', 'Kesehatan', 'Sosial', 'Lingkungan', 'Bencana', 'Keagamaan'];
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Dapatkan preferensi kategori relawan saat ini
     */
    public function getMyPreferences(Request $request)
    {
        $user = $request->user();
        
        $preferences = $user->categoryPreferences()
            ->where('is_preferred', true)
            ->orderByEngagement()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $preferences->map(fn($p) => [
                'id' => $p->id,
                'category' => $p->category,
                'is_preferred' => $p->is_preferred,
                'auto_discovered' => $p->auto_discovered,
                'engagement_score' => $p->engagement_score,
            ])
        ]);
    }

    /**
     * Update preferensi kategori relawan
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'string|in:Pendidikan,Kesehatan,Sosial,Lingkungan,Bencana,Keagamaan'
        ]);

        $user = $request->user();
        $newCategories = $request->input('categories', []);

        // Hapus preferensi lama
        $user->categoryPreferences()->where('is_preferred', true)->delete();

        // Tambah preferensi baru
        foreach ($newCategories as $category) {
            VolunteerCategoryPreference::updateOrCreate(
                ['user_id' => $user->id, 'category' => $category],
                [
                    'is_preferred' => true,
                    'auto_discovered' => false,
                ]
            );
        }

        // Auto-discover kategori dari riwayat
        $this->autoDiscoverCategories($user);

        return response()->json([
            'success' => true,
            'message' => 'Preferensi kategori berhasil diperbarui',
            'data' => $user->categoryPreferences()->get()
        ]);
    }

    /**
     * Auto-discover kategori dari riwayat relawan
     */
    public function autoDiscoverCategories(User $user)
    {
        // Dapatkan semua kategori dari kampanye yang pernah diikuti relawan
        $volunteerHistories = VolunteerApplication::where('user_id', $user->id)
            ->with('campaign')
            ->get();

        $categories = $volunteerHistories->map(function($history) {
            return $history->campaign->kategori;
        })->unique()->values();

        // Simpan sebagai auto-discovered preferences
        foreach ($categories as $category) {
            $existing = VolunteerCategoryPreference::where('user_id', $user->id)
                ->where('category', $category)
                ->first();

            if ($existing) {
                // Tingkatkan engagement score
                $existing->increment('engagement_score');
                if (!$existing->auto_discovered) {
                    $existing->update(['auto_discovered' => true]);
                }
            } else {
                VolunteerCategoryPreference::create([
                    'user_id' => $user->id,
                    'category' => $category,
                    'is_preferred' => true,
                    'auto_discovered' => true,
                    'engagement_score' => 1,
                ]);
            }
        }

        return true;
    }

    /**
     * Dapatkan kampanye yang direkomendasikan untuk relawan
     */
    public function getRecommendations(Request $request)
    {
        $user = $request->user();
        $limit = $request->input('limit', 10);

        // Dapatkan preferensi kategori aktif user
        $preferredCategories = $user->categoryPreferences()
            ->where('is_preferred', true)
            ->pluck('category')
            ->toArray();

        if (empty($preferredCategories)) {
            // Jika tidak ada preferensi, auto-discover dari riwayat
            $this->autoDiscoverCategories($user);
            $preferredCategories = $user->categoryPreferences()
                ->where('is_preferred', true)
                ->pluck('category')
                ->toArray();
        }

        // Dapatkan ID kampanye yang sudah diikuti relawan
        $appliedCampaignIds = VolunteerApplication::where('user_id', $user->id)
            ->pluck('volunteer_campaign_id')
            ->toArray();

        // Query kampanye yang sesuai dengan kategori preferensi, exclude yang sudah diikuti
        $recommendations = VolunteerCampaign::whereIn('kategori', $preferredCategories)
            ->whereNotIn('id', $appliedCampaignIds)
            ->where('status', 'Aktif')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function($campaign) use ($preferredCategories) {
                return [
                    'id' => $campaign->id,
                    'judul' => $campaign->judul,
                    'slug' => $campaign->slug,
                    'image' => $campaign->image,
                    'deskripsi' => $campaign->deskripsi,
                    'kategori' => $campaign->kategori,
                    'lokasi' => $campaign->lokasi,
                    'tanggal_mulai' => $campaign->tanggal_mulai,
                    'tanggal_selesai' => $campaign->tanggal_selesai,
                    'kuota_total' => $campaign->kuota_total,
                    'kuota_terisi' => $campaign->kuota_terisi,
                    'status' => $campaign->status,
                    'matching_reason' => "Sesuai dengan kategori {$campaign->kategori} yang pernah Anda ikuti",
                ];
            });

        return response()->json([
            'success' => true,
            'total' => count($recommendations),
            'data' => $recommendations
        ]);
    }

    /**
     * Dapatkan notifikasi rekomendasi untuk relawan
     */
    public function getRecommendationNotifications(Request $request)
    {
        $user = $request->user();
        $limit = $request->input('limit', 10);

        $notifications = $user->recommendationNotifications()
            ->active()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'campaign' => [
                        'id' => $notification->campaign->id,
                        'judul' => $notification->campaign->judul,
                        'slug' => $notification->campaign->slug,
                        'image' => $notification->campaign->image,
                        'kategori' => $notification->campaign->kategori,
                        'lokasi' => $notification->campaign->lokasi,
                        'status' => $notification->campaign->status,
                    ],
                    'matching_category' => $notification->matching_category,
                    'reason' => $notification->reason,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'total' => count($notifications),
            'data' => $notifications
        ]);
    }

    /**
     * Mark recommendation notification as read
     */
    public function markRecommendationAsRead(Request $request, $notificationId)
    {
        $user = $request->user();
        $notification = $user->recommendationNotifications()
            ->findOrFail($notificationId);

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca'
        ]);
    }

    /**
     * Dismiss recommendation notification
     */
    public function dismissRecommendation(Request $request, $notificationId)
    {
        $user = $request->user();
        $notification = $user->recommendationNotifications()
            ->findOrFail($notificationId);

        $notification->markAsDismissed();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi rekomendasi ditutup'
        ]);
    }

    /**
     * Generate recommendations untuk kampanye baru
     * (Dipanggil dari VolunteerCampaignObserver saat kampanye dibuat)
     */
    public function generateRecommendations(VolunteerCampaign $campaign)
    {
        $campaignCategory = $campaign->kategori;

        // Dapatkan semua relawan yang punya preferensi untuk kategori ini
        $relevantUsers = VolunteerCategoryPreference::where('category', $campaignCategory)
            ->where('is_preferred', true)
            ->with('user')
            ->get();

        $created = 0;
        foreach ($relevantUsers as $preference) {
            $user = $preference->user;

            // Cek apakah relawan sudah pernah mendaftar kampanye ini
            $alreadyApplied = VolunteerApplication::where('user_id', $user->id)
                ->where('volunteer_campaign_id', $campaign->id)
                ->exists();

            if (!$alreadyApplied) {
                // Buat recommendation notification
                try {
                    RecommendationNotification::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'campaign_id' => $campaign->id,
                        ],
                        [
                            'matching_category' => $campaignCategory,
                            'reason' => "Kampanye '{$campaign->judul}' sesuai dengan kategori '{$campaignCategory}' yang pernah Anda ikuti.",
                        ]
                    );

                    // Kirim notifikasi database (dapat dikembangkan ke email/SMS)
                    $user->notify(new \App\Notifications\RecommendedCampaignNotification(
                        $campaign,
                        $campaignCategory
                    ));

                    $created++;
                } catch (\Exception $e) {
                    \Log::error("Gagal membuat rekomendasi untuk user {$user->id}: " . $e->getMessage());
                }
            }
        }

        return [
            'success' => true,
            'created' => $created,
            'campaign_id' => $campaign->id,
        ];
    }
}
