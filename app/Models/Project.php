<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Project extends Model
{
    use LogsActivity;
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
