<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const PROVIDER_MIDTRANS_RENTAL = 'midtrans';

    public const PROVIDER_MIDTRANS_DAMAGE = 'midtrans_damage';

    protected $fillable = [
        'booking_id',
        'order_id',
        'provider',
        'midtrans_order_id',
        'payment_type',
        'snap_token',
        'transaction_id',
        'transaction_status',
        'gross_amount',
        'status',
        'paid_at',
        'expired_at',
        'payload_json',
    ];

    protected $casts = [
        'gross_amount' => 'integer',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isDamageFee(): bool
    {
        return (string) $this->provider === self::PROVIDER_MIDTRANS_DAMAGE;
    }
}
