<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomelabService extends Model
{
    protected $fillable = [
        'vmid',
        'name',
        'node_label',
        'icon',
        'type',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true)->orderBy('sort_order');
    }
}
