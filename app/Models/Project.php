<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'type',
        'tech_stack',
        'url',
    ];

    protected $casts = [
        'tech_stack' => 'array',
    ];
}
