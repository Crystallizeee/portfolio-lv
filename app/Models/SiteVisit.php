<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
        'ip_address',
        'url',
        'user_agent',
        'referer',
        'visitor_id',
    ];

    protected $casts = [
        'visitor_id' => 'string',
    ];
}
