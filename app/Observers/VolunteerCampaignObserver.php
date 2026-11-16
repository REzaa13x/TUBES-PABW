<?php

namespace App\Observers;

use App\Models\VolunteerCampaign;
use App\Http\Controllers\Api\VolunteerRecommendationController;

class VolunteerCampaignObserver
{
    /**
     * Handle the VolunteerCampaign "created" event.
     */
    public function created(VolunteerCampaign $campaign): void
    {
        // Trigger generate recommendations ketika kampanye baru dibuat
        try {
            $controller = new VolunteerRecommendationController();
            $result = $controller->generateRecommendations($campaign);
            
            \Log::info("Rekomendasi kampanye {$campaign->id} berhasil dibuat untuk {$result['created']} relawan");
        } catch (\Exception $e) {
            \Log::error("Gagal generate rekomendasi untuk kampanye {$campaign->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the VolunteerCampaign "updated" event.
     */
    public function updated(VolunteerCampaign $campaign): void
    {
        // Optional: Trigger ulang rekomendasi jika kategori berubah
        if ($campaign->isDirty('kategori')) {
            try {
                $controller = new VolunteerRecommendationController();
                $result = $controller->generateRecommendations($campaign);
                
                \Log::info("Rekomendasi kampanye {$campaign->id} di-update untuk kategori baru");
            } catch (\Exception $e) {
                \Log::error("Gagal update rekomendasi untuk kampanye {$campaign->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the VolunteerCampaign "deleted" event.
     */
    public function deleted(VolunteerCampaign $campaign): void
    {
        // Hapus notifikasi rekomendasi yang terkait
        \App\Models\RecommendationNotification::where('campaign_id', $campaign->id)->delete();
    }

    /**
     * Handle the VolunteerCampaign "restored" event.
     */
    public function restored(VolunteerCampaign $campaign): void
    {
        //
    }

    /**
     * Handle the VolunteerCampaign "force deleted" event.
     */
    public function forceDeleted(VolunteerCampaign $campaign): void
    {
        //
    }
}
