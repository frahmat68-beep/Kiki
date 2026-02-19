<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class PhoneOtpService
{
    public function sendOtp(User $user, string $phone, string $otp): void
    {
        // Default driver: log. Replace with SMS/WA provider later.
        Log::channel(config('logging.default'))->info('PHONE_OTP_SENT', [
            'user_id' => $user->id,
            'email' => $user->email,
            'phone' => $phone,
            'otp' => $otp,
            'driver' => 'log',
            'sent_at' => now()->toIso8601String(),
        ]);
    }
}
