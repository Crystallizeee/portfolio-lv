<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
        'ip_address',
        'ip_hash',
        'url',
        'user_agent',
        'referer',
        'visitor_id',
    ];

    protected $casts = [
        'visitor_id' => 'string',
    ];

    /**
     * Get IP address or fallback for old anonymized records.
     */
    public function getIpAddressAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        if (!empty($this->ip_hash)) {
            return 'Anon (' . substr($this->ip_hash, 0, 8) . '...)';
        }

        return 'Unknown IP';
    }
}
