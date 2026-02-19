<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMedia extends Model
{
    protected $table = 'site_media';

    protected $fillable = [
        'key',
        'path',
        'disk',
        'alt_text',
        'group',
        'uploaded_by_admin_id',
    ];

    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by_admin_id');
    }
}

