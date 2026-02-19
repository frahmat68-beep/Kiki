<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'equipment_id',
        'qty',
        'price',
        'subtotal',
        'rental_start_date',
        'rental_end_date',
        'rental_days',
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'rental_days' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
}
