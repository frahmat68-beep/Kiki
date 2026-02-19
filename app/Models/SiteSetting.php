<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'updated_by_admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by_admin_id');
    }
}
