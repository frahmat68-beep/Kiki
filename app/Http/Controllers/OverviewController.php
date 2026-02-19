<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class OverviewController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return redirect()->route('booking.history');
    }

    public function index(): RedirectResponse
    {
        return redirect()->route('booking.history');
    }
}
