<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McpMessage extends Model
{
    protected $fillable = ['session_id', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];
}
