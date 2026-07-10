<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'provider',
        'preference_id',
        'provider_payment_id',
        'status',
        'amount',
        'currency',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'amount'      => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}