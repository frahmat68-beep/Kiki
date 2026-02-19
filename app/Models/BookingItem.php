<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    protected $table = 'booking_items';

    protected $fillable = [
        'booking_id',
        'equipment_id',
        'quantity',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
