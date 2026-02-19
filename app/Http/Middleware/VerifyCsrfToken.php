<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        // Avoid 419 when session/token on old tab has expired and user clicks logout.
        'logout',
        'admin/logout',
        'payment/callback',
        'midtrans/callback',
        'api/midtrans/callback',
    ];
}
