<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VolunteerCampaign;

class RecommendedCampaignNotification extends Notification
{
    use Queueable;

    protected $campaign;
    protected $matchingCategory;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(VolunteerCampaign $campaign, string $matchingCategory, string $reason = null)
    {
        $this->campaign = $campaign;
        $this->matchingCategory = $matchingCategory;
        $this->reason = $reason ?? "Kampanye ini sesuai dengan kategori '{$matchingCategory}' yang pernah Anda ikuti.";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'recommendation',
            'title' => '⭐ Kampanye Baru Sesuai Minat Anda',
            'message' => "Karena Anda pernah mengikuti kegiatan kategori {$this->matchingCategory}, kami menemukan kampanye baru yang mungkin sesuai dengan pengalaman dan minat Anda.",
            'campaign' => [
                'id' => $this->campaign->id,
                'judul' => $this->campaign->judul,
                'slug' => $this->campaign->slug,
                'image' => $this->campaign->image,
                'kategori' => $this->campaign->kategori,
                'lokasi' => $this->campaign->lokasi,
                'status' => $this->campaign->status,
                'tanggal_mulai' => $this->campaign->tanggal_mulai,
                'deskripsi' => substr($this->campaign->deskripsi, 0, 100) . '...',
            ],
            'matching_category' => $this->matchingCategory,
            'reason' => $this->reason,
            'icon' => 'fas fa-star',
            'color' => 'text-yellow-500',
            'badge' => 'Rekomendasi',
            'action_url' => route('api.volunteer-campaigns.show', $this->campaign->id),
            'created_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⭐ Kampanye Baru Sesuai Minat Anda di Dongiv')
            ->greeting("Halo {$notifiable->name}!")
            ->line("Karena Anda pernah mengikuti kegiatan kategori {$this->matchingCategory}, kami menemukan kampanye baru yang mungkin sesuai dengan pengalaman dan minat Anda.")
            ->line("**Kampanye:** {$this->campaign->judul}")
            ->line("**Kategori:** {$this->campaign->kategori}")
            ->line("**Lokasi:** {$this->campaign->lokasi}")
            ->action('Lihat Detail Kampanye', route('api.volunteer-campaigns.show', $this->campaign->id))
            ->line('Tetap semangat menyebarkan kebaikan bersama Dongiv 💙');
    }
}
