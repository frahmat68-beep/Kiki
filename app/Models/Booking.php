<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
    'user_id',
    'start_date',
    'end_date',
    'status',
    'reference',
    'resi',
    'paid_at',
    'returned_at',
];



    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }
}
