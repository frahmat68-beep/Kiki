<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'otp_hash',
        'otp_expires_at',
        'otp_attempts',
        'last_requested_at',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'last_requested_at' => 'datetime',
        'otp_attempts' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
