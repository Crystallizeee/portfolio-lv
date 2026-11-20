<?php

namespace App\Models;

use App\Services\WebPushService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Contracts\Sitemapable;

class Post extends Model implements Sitemapable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'tags',
        'category',
        'status',
        'published_at',
        'notified_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'notified_at'  => 'datetime',
        'tags'         => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    protected static function booted(): void
    {
        static::saved(function (self $post) {
            Cache::forget('latest_post_notifications');

            if (
                $post->status === 'published' &&
                $post->published_at !== null &&
                $post->published_at->isPast() &&
                $post->notified_at === null
            ) {
                try {
                    app(WebPushService::class)->notifyNewPost($post);
                    $post->updateQuietly(['notified_at' => now()]);
                } catch (\Throwable $e) {
                    Log::warning('Failed to send WebPush for post', [
                        'post_id' => $post->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        static::deleted(fn () => Cache::forget('latest_post_notifications'));
    }

    /**
     * Get the latest published posts for visitor in-app notifications.
     */
    public static function latestNotifications(int $limit = 5): array
    {
        return Cache::remember('latest_post_notifications', 300, function () use ($limit) {
            return static::published()
                ->latest('published_at')
                ->take($limit)
                ->get(['id', 'title', 'slug', 'category', 'published_at'])
                ->map(fn (self $post) => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'category' => $post->category ?: 'Article',
                    'published_human' => $post->published_at?->diffForHumans() ?? '',
                    'url' => route('blog.show', $post->slug),
                ])
                ->all();
        });
    }

    public function toSitemapTag(): \Spatie\Sitemap\Tags\Url | string | array
    {
        return \Spatie\Sitemap\Tags\Url::create(route('blog.show', $this->slug))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.7);
    }
}
