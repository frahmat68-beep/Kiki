<?php

namespace App\Services;

use App\Models\User;

class EmailOtpService
{
    public function sendRegistrationOtp(User $user): void
    {
        // TODO: generate OTP, store hash + expiry, and send email.
    }

    public function verifyRegistrationOtp(User $user, string $code): bool
    {
        // TODO: verify OTP and mark user as verified.
        return false;
    }
}
