<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Equipment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalBooking'   => Booking::count(),
            'pendingBooking' => Booking::where('status', 'pending')->count(),
            'paidBooking'    => Booking::where('status', 'paid')->count(),
            'totalEquipment' => Equipment::count(),
            'latestBookings' => Booking::latest()->take(5)->get(),
        ]);
    }
}
