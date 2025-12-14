<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerCampaign extends Model
{
    use HasFactory;

    // 1. Beritahu Laravel nama tabelnya (karena tidak standar 'campaigns')
    protected $table = 'volunteer_campaigns';

    // 2. Izinkan semua kolom diisi (Mass Assignment)
    protected $guarded = ['id'];

    // 3. Helper untuk Progress Bar Frontend
    public function getProgressAttribute()
    {
        if ($this->kuota_total == 0) return 0;
        return round(($this->kuota_terisi / $this->kuota_total) * 100);
    }

    public function volunteers()
    {
        return $this->hasMany(VolunteerApplication::class, 'volunteer_campaign_id');
    }
}
