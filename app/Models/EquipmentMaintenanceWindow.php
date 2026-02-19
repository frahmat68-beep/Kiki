<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentMaintenanceWindow extends Model
{
    protected $fillable = [
        'equipment_id',
        'start_date',
        'end_date',
        'reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
