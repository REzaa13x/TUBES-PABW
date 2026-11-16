<?php

namespace App\Notifications;

use App\Models\VolunteerCertificate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CertificateIssued extends Notification
{
    use Queueable;

    public function __construct(public VolunteerCertificate $certificate) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'        => 'certificate_issued',
            'title'       => '🎓 Sertifikat Anda Telah Tersedia',
            'message'     => 'Sertifikat digital Anda untuk kegiatan "' . $this->certificate->campaign_name . '" telah siap diunduh.',
            'action_url'  => route('certificates.show', $this->certificate->id),
            'action_label' => 'Lihat Sertifikat',
            'certificate_number' => $this->certificate->certificate_number,
            'campaign_name' => $this->certificate->campaign_name,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎓 Sertifikat Relawan Dongiv Anda Tersedia')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Sertifikat digital Anda untuk kegiatan "' . $this->certificate->campaign_name . '" telah siap.')
            ->action('Lihat Sertifikat', route('certificates.show', $this->certificate->id))
            ->line('Terima kasih atas kontribusi Anda!');
    }
}
