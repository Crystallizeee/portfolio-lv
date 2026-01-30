<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Experience extends Model
{
    use LogsActivity;
    protected $fillable = [
        'company',
        'role',
        'type',
        'date_range',
        'description',
        'sort_order',
    ];
}
