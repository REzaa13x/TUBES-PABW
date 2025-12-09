<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'donor_name',
        'donor_email',
        'donor_phone',
        'user_id',
        'status',
        'payment_method',
        'payment_method_data',
        'campaign_id',
        'transfer_deadline',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'proof_of_transfer_path',
        'transfer_completed_at',
        'verified_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transfer_deadline' => 'datetime',
        'transfer_completed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coinHistory()
    {
        return $this->hasOne(CoinHistory::class, 'transaction_id');
    }

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'AWAITING_TRANSFER' => 'Pending',
            'PENDING_VERIFICATION' => 'Waiting',
            'VERIFIED' => 'Paid',
            'CANCELLED' => 'Rejected'
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }
}
