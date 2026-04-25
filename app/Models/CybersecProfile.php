<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CybersecProfile extends Model
{
    protected $fillable = [
        'user_id',
        'platform',
        'username',
        'profile_url',
        'rank',
        'rooms_completed',
        'badges_count',
        'streak',
        'points',
        'top_percent',
        'custom_stats',
        'is_visible',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'custom_stats' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the badge image URL for TryHackMe
     */
    public function getBadgeImageUrlAttribute(): ?string
    {
        if ($this->platform === 'tryhackme') {
            return "https://tryhackme-badges.s3.amazonaws.com/{$this->username}.png";
        }

        return null;
    }

    /**
     * Auto-generate profile URL based on platform and username
     */
    public function getGeneratedProfileUrlAttribute(): string
    {
        return match ($this->platform) {
            'tryhackme' => "https://tryhackme.com/p/{$this->username}",
            'letsdefend' => "https://app.letsdefend.io/user/{$this->username}",
            default => '#',
        };
    }

    /**
     * Get the platform display name
     */
    public function getPlatformNameAttribute(): string
    {
        return match ($this->platform) {
            'tryhackme' => 'TryHackMe',
            'letsdefend' => 'LetsDefend',
            default => ucfirst($this->platform),
        };
    }

    /**
     * Scope to get visible profiles ordered
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true)->orderBy('sort_order');
    }
}
