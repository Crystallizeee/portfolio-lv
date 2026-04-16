<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'professional_title',
        'summary',
        'about_grc_list',
        'about_tech_list',
        'is_landing_page',
    ];

    protected $casts = [
        'about_grc_list' => 'array',
        'about_tech_list' => 'array',
        'is_landing_page' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
