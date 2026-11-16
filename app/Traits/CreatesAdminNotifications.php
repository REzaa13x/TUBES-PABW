<?php

namespace App\Traits;

use App\Models\AdminNotification;

/**
 * Trait untuk membuat notifikasi admin dari berbagai events
 * 
 * Gunakan di Controller atau Model untuk generate notifikasi
 */
trait CreatesAdminNotifications
{
    /**
     * Notifikasi: Kampanye Baru Menunggu Verifikasi
     */
    public static function notifyNewCampaignPending($campaign)
    {
        return AdminNotification::create([
            'title' => '📢 Kampanye Baru Menunggu Verifikasi',
            'message' => "Kampanye \"{$campaign->title}\" dibuat oleh {$campaign->user->name} dan menunggu persetujuan admin.",
            'category' => 'kampanye',
            'priority' => 'high',
            'icon' => '📢',
            'type' => 'campaign_pending_approval',
            'action_label' => 'Lihat Detail',
            'action_url' => route('admin.campaigns.show', $campaign->id),
            'secondary_action_label' => 'Setujui',
            'secondary_action_url' => route('admin.campaigns.approve', $campaign->id),
            'related_campaign_id' => $campaign->id,
            'related_user_id' => $campaign->user_id,
            'metadata' => [
                'campaign_title' => $campaign->title,
                'creator_name' => $campaign->user->name,
                'target_amount' => $campaign->target_amount,
            ],
        ]);
    }

    /**
     * Notifikasi: Donasi Baru Masuk
     */
    public static function notifyNewDonation($donation)
    {
        $campaign = $donation->campaign;
        
        return AdminNotification::create([
            'title' => '💰 Donasi Baru Masuk',
            'message' => "Donasi sebesar " . self::formatCurrency($donation->amount) . " berhasil masuk ke kampanye \"{$campaign->title}\".",
            'category' => 'donasi',
            'priority' => 'high',
            'icon' => '💰',
            'type' => 'donation_received',
            'action_label' => 'Lihat Transaksi',
            'action_url' => route('admin.donations.show', $donation->order_id ?? $donation->id),
            'secondary_action_label' => 'Lihat Campaign',
            'secondary_action_url' => route('admin.campaigns.show', $campaign->id),
            'related_donation_id' => $donation->id,
            'related_campaign_id' => $campaign->id,
            'metadata' => [
                'amount' => $donation->amount,
                'campaign_title' => $campaign->title,
                'donor_name' => $donation->user->name ?? 'Anonymous',
            ],
        ]);
    }

    /**
     * Notifikasi: Relawan Baru Mendaftar
     */
    public static function notifyNewVolunteer($volunteer)
    {
        return AdminNotification::create([
            'title' => '🤝 Relawan Baru Mendaftar',
            'message' => "{$volunteer->name} telah mendaftar sebagai relawan.",
            'category' => 'relawan',
            'priority' => 'medium',
            'icon' => '🤝',
            'type' => 'volunteer_registered',
            'action_label' => 'Lihat Profil',
            'action_url' => route('admin.volunteers.show', $volunteer->id),
            'related_volunteer_id' => $volunteer->id,
            'related_user_id' => $volunteer->user_id,
            'metadata' => [
                'volunteer_name' => $volunteer->name,
                'skills' => $volunteer->skills ?? [],
            ],
        ]);
    }

    /**
     * Notifikasi: Laporan Pengguna Baru
     */
    public static function notifyNewReport($report)
    {
        return AdminNotification::create([
            'title' => '🚩 Laporan Baru Masuk',
            'message' => "Laporan baru diterima untuk kampanye \"{$report->campaign->title}\" dari {$report->user->name}.",
            'category' => 'laporan',
            'priority' => 'high',
            'icon' => '🚩',
            'type' => 'report_received',
            'action_label' => 'Tinjau Laporan',
            'action_url' => route('admin.reports.show', $report->id),
            'secondary_action_label' => 'Hubungi Pembuat',
            'secondary_action_url' => route('admin.users.show', $report->campaign->user_id),
            'related_campaign_id' => $report->campaign_id,
            'related_user_id' => $report->user_id,
            'metadata' => [
                'report_reason' => $report->reason ?? '',
                'reporter_name' => $report->user->name,
            ],
        ]);
    }

    /**
     * Notifikasi: Smart Matching Relawan
     */
    public static function notifySmartMatching($campaign, $matchedVolunteers)
    {
        $count = count($matchedVolunteers);
        
        return AdminNotification::create([
            'title' => '⭐ Smart Matching Terdeteksi',
            'message' => "Kampanye \"{$campaign->title}\" memiliki {$count} relawan yang sesuai berdasarkan kategori.",
            'category' => 'smart_matching',
            'priority' => 'medium',
            'icon' => '⭐',
            'type' => 'volunteer_matching_found',
            'action_label' => 'Lihat Kandidat',
            'action_url' => route('admin.campaigns.volunteers', $campaign->id),
            'secondary_action_label' => 'Kirim Undangan',
            'secondary_action_url' => route('admin.volunteers.invite', $campaign->id),
            'related_campaign_id' => $campaign->id,
            'metadata' => [
                'campaign_title' => $campaign->title,
                'matched_count' => $count,
                'volunteer_ids' => array_pluck($matchedVolunteers, 'id') ?? [],
            ],
        ]);
    }

    /**
     * Notifikasi: Kampanye Hampir Berakhir
     */
    public static function notifyCampaignEnding($campaign, $daysLeft)
    {
        return AdminNotification::create([
            'title' => '⚠️ Kampanye Akan Berakhir',
            'message' => "Kampanye \"{$campaign->title}\" akan berakhir dalam {$daysLeft} hari.",
            'category' => 'kampanye',
            'priority' => 'medium',
            'icon' => '⚠️',
            'type' => 'campaign_ending_soon',
            'action_label' => 'Lihat Kampanye',
            'action_url' => route('admin.campaigns.show', $campaign->id),
            'related_campaign_id' => $campaign->id,
            'metadata' => [
                'campaign_title' => $campaign->title,
                'days_left' => $daysLeft,
                'end_date' => $campaign->end_date,
            ],
        ]);
    }

    /**
     * Notifikasi: Target Kampanye Tercapai
     */
    public static function notifyCampaignTargetReached($campaign, $totalDonated)
    {
        return AdminNotification::create([
            'title' => '🎉 Target Kampanye Tercapai',
            'message' => "Kampanye \"{$campaign->title}\" berhasil mencapai target donasi sebesar " . self::formatCurrency($totalDonated) . ".",
            'category' => 'kampanye',
            'priority' => 'medium',
            'icon' => '🎉',
            'type' => 'campaign_target_reached',
            'action_label' => 'Lihat Detail',
            'action_url' => route('admin.campaigns.show', $campaign->id),
            'related_campaign_id' => $campaign->id,
            'metadata' => [
                'campaign_title' => $campaign->title,
                'total_donated' => $totalDonated,
                'target_amount' => $campaign->target_amount,
            ],
        ]);
    }

    /**
     * Notifikasi: Aktivitas Sistem
     */
    public static function notifySystemActivity($activityTitle, $activityMessage, $metadata = [])
    {
        return AdminNotification::create([
            'title' => '⚙️ ' . $activityTitle,
            'message' => $activityMessage,
            'category' => 'sistem',
            'priority' => 'low',
            'icon' => '⚙️',
            'type' => 'system_activity',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Format currency
     */
    protected static function formatCurrency($amount)
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
