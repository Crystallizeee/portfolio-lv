<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytics extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'count',
        'date',
        'ip_address', // Added
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Increment a specific analytics type for today
     */
    public static function track(int $userId, string $type): void
    {
        $today = now()->toDateString();
        $ip = request()->ip(); // Capture IP
        
        $record = self::firstOrCreate(
            [
                'user_id' => $userId, 
                'type' => $type, 
                'date' => $today,
                'ip_address' => $ip
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
