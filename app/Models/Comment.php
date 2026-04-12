<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'name',
        'content',
        'is_approved',
        'ip_hash',
        'spam_score',
        'honeypot_triggered',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'honeypot_triggered' => 'boolean',
    ];

    /**
     * Scope: only approved (visible) comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope: pending moderation.
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
