<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'company',
        'role',
        'type',
        'date_range',
        'description',
        'sort_order',
    ];
}
