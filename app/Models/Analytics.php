<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\IpAnonymizer;

class Analytics extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'count',
        'date',
        'ip_hash',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Increment a specific analytics type for today.
     * IP is hashed for privacy compliance (UU PDP).
     */
    public static function track(int $userId, string $type): void
    {
        $today = now()->toDateString();
        $ipHash = IpAnonymizer::hashRequest();
        
        $record = self::firstOrCreate(
            [
                'user_id' => $userId, 
                'type' => $type, 
                'date' => $today,
                'ip_hash' => $ipHash
            ],
            ['count' => 0]
        );
        
        $record->increment('count');
    }

    /**
     * Get total count for a specific type
     */
    public static function getTotal(int $userId, string $type): int
    {
        return self::where('user_id', $userId)
            ->where('type', $type)
            ->sum('count');
    }
}
