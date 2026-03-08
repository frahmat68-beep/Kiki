<?php

return [
    'otp_required' => (bool) env('OTP_REQUIRED', false),
    'login_max_attempts' => (int) env('LOGIN_MAX_ATTEMPTS', 5),
    'login_lockout_seconds' => (int) env('LOGIN_LOCKOUT_SECONDS', 300),
    'admin_login_max_attempts' => (int) env('ADMIN_LOGIN_MAX_ATTEMPTS', 5),
    'admin_login_lockout_seconds' => (int) env('ADMIN_LOGIN_LOCKOUT_SECONDS', 300),
    'otp_ttl_minutes' => (int) env('OTP_TTL_MINUTES', 5),
    'otp_verify_max_attempts' => (int) env('OTP_VERIFY_MAX_ATTEMPTS', 5),
    'otp_verify_lockout_minutes' => (int) env('OTP_VERIFY_LOCKOUT_MINUTES', 60),
    'otp_resend_cooldown_seconds' => (int) env('OTP_RESEND_COOLDOWN_SECONDS', 60),
    'otp_resend_max_per_hour' => (int) env('OTP_RESEND_MAX_PER_HOUR', 5),
    'phone_otp_ttl_minutes' => (int) env('PHONE_OTP_TTL_MINUTES', 5),
    'phone_otp_max_attempts' => (int) env('PHONE_OTP_MAX_ATTEMPTS', 5),
    'phone_otp_resend_cooldown_seconds' => (int) env('PHONE_OTP_RESEND_COOLDOWN_SECONDS', 60),
    'phone_otp_resend_max_per_hour' => (int) env('PHONE_OTP_RESEND_MAX_PER_HOUR', 5),
    'email_verification_resend_cooldown_seconds' => (int) env('EMAIL_VERIFY_RESEND_COOLDOWN_SECONDS', 60),
    'email_verification_resend_max_per_hour' => (int) env('EMAIL_VERIFY_RESEND_MAX_PER_HOUR', 5),
];
