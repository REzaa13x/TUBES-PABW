<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VolunteerCertificate extends Model
{
    use HasFactory;

    protected $table = 'volunteer_certificates';

    protected $fillable = [
        'certificate_id',
        'certificate_number',
        'volunteer_id',
        'volunteer_name',
        'campaign_id',
        'campaign_name',
        'category',
        'location',
        'start_date',
        'end_date',
        'application_id',
        'issue_date',
        'volunteer_level',
        'total_volunteer_hours',
        'organizer_name',
        'status',
        'certificate_file',
        'qr_code',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'issue_date' => 'date',
        'metadata'   => 'array',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function campaign()
    {
        return $this->belongsTo(VolunteerCampaign::class, 'campaign_id');
    }

    public function application()
    {
        return $this->belongsTo(VolunteerApplication::class, 'application_id');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDownloadable($query)
    {
        // Bisa diunduh hanya jika status active (kampanye sudah selesai)
        return $query->where('status', 'active');
    }

    // ─── Computed / Accessors ────────────────────────────────────────────────

    public function getIsDownloadableAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function getVerifyUrlAttribute(): string
    {
        return route('certificate.verify', $this->certificate_number);
    }

    public function getQrUrlAttribute(): string
    {
        return route('api.certificate.qr', ['certificateNumber' => $this->certificate_number]);
    }

    public function getQrPngAttribute(): string
    {
        return (new \BaconQrCode\Writer(new \BaconQrCode\Renderer\GDLibRenderer(256)))
            ->writeString($this->verify_url);
    }

    public function getQrDataUriAttribute(): string
    {
        return 'data:image/png;base64,'.base64_encode($this->qr_png);
    }
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Kampanye Selesai',
            'active'  => 'Aktif',
            'revoked' => 'Dicabut',
            default   => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'active'  => 'green',
            'revoked' => 'red',
            default   => 'gray',
        };
    }

    // ─── Static Generators ───────────────────────────────────────────────────

    /**
     * Generate nomor sertifikat unik: DGV-VOL-YYYY-XXXX
     */
    public static function generateCertificateNumber(): string
    {
        $year  = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'DGV-VOL-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Tentukan level relawan berdasarkan total jam
     */
    public static function determineLevel(int $hours): string
    {
        if ($hours >= 200) return 'Relawan Ahli';
        if ($hours >= 100) return 'Relawan Senior';
        if ($hours >= 40)  return 'Relawan Aktif';
        return 'Relawan Baru';
    }

    /**
     * Hitung total jam dari durasi kampanye (tanggal_mulai - tanggal_selesai)
     */
    public static function calculateHours(VolunteerCampaign $campaign): int
    {
        if (!$campaign->tanggal_mulai || !$campaign->tanggal_selesai) return 8;
        $days = Carbon::parse($campaign->tanggal_mulai)->diffInDays(Carbon::parse($campaign->tanggal_selesai));
        return max(8, $days * 8); // Minimal 8 jam, asumsi 8 jam/hari
    }

    /**
     * Generate sertifikat otomatis dari VolunteerApplication yang sudah diapprove.
     * Status awal = 'pending' (belum bisa diunduh sampai kampanye selesai).
     * Jika kampanye sudah selesai saat approved, langsung 'active'.
     */
    public static function generateForApplication(VolunteerApplication $application): self
    {
        // Cek jika sudah ada
        $existing = self::where('application_id', $application->id)->first();
        if ($existing) return $existing;

        $campaign   = $application->campaign;
        $user       = $application->user;
        $hours      = self::calculateHours($campaign);

        // Total jam akumulasi semua sertifikat aktif relawan ini
        $totalHours = self::where('volunteer_id', $user->id)
            ->where('status', 'active')
            ->sum('total_volunteer_hours') + $hours;

        $level  = self::determineLevel($totalHours);
        $certId = (string) Str::uuid();
        $certNo = self::generateCertificateNumber();

        // Tentukan status awal berdasarkan apakah kampanye sudah selesai
        $campaignEnded = $campaign->tanggal_selesai && Carbon::parse($campaign->tanggal_selesai)->isPast();
        $status        = $campaignEnded ? 'active' : 'pending';

        $qrCode = null; // QR is generated from the current hosting domain when requested.

        return self::create([
            'certificate_id'       => $certId,
            'certificate_number'   => $certNo,
            'volunteer_id'         => $user->id,
            'volunteer_name'       => $user->name,
            'campaign_id'          => $campaign->id,
            'campaign_name'        => $campaign->judul,
            'category'             => $campaign->kategori,
            'location'             => $campaign->lokasi,
            'start_date'           => $campaign->tanggal_mulai,
            'end_date'             => $campaign->tanggal_selesai,
            'application_id'       => $application->id,
            'issue_date'           => now()->toDateString(),
            'volunteer_level'      => $level,
            'total_volunteer_hours' => $hours,
            'organizer_name'       => 'Dongiv',
            'status'               => $status,
            'qr_code'              => $qrCode,
            'metadata'             => [
                'certificate_uuid' => $certId,
                'issued_by'        => 'Dongiv Platform',
                'source'           => 'auto_generated',
            ],
        ]);
    }

    /**
     * Aktifkan sertifikat yang masih pending (dipanggil via scheduler atau manual admin).
     */
    public function activate(): bool
    {
        if ($this->status !== 'pending') return false;
        return $this->update(['status' => 'active']);
    }

    /**
     * Cabut sertifikat.
     */
    public function revoke(): bool
    {
        return $this->update(['status' => 'revoked']);
    }
}
