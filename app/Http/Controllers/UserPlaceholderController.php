<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class UserPlaceholderController extends Controller
{
    public function notifications(): View
    {
        return view('placeholder', [
            'title_key' => 'ui.placeholders.notifications_title',
            'message_key' => 'ui.placeholders.notifications_message',
        ]);
    }

    public function bookingIndex(): View
    {
        return view('placeholder', [
            'title_key' => 'ui.placeholders.booking_title',
            'message_key' => 'ui.placeholders.booking_message',
        ]);
    }

    public function bookingHistory(): View
    {
        return view('placeholder', [
            'title_key' => 'ui.placeholders.booking_history_title',
            'message_key' => 'ui.placeholders.booking_history_message',
        ]);
    }

    public function bookingPay(string $id): View
    {
        return view('placeholder', [
            'title_key' => 'ui.placeholders.booking_payment_title',
            'message_key' => 'ui.placeholders.booking_payment_message',
            'message_params' => ['id' => $id],
        ]);
    }

    public function bookingShow(string $id): View
    {
        return view('placeholder', [
            'title_key' => 'ui.placeholders.booking_detail_title',
            'message_key' => 'ui.placeholders.booking_detail_message',
            'message_params' => ['id' => $id],
        ]);
    }
}
