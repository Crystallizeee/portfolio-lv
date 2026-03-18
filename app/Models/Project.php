<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\HasSeo;

use Spatie\Sitemap\Contracts\Sitemapable;

class Project extends Model implements Sitemapable
{
    use LogsActivity, HasSeo;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'challenge',
        'solution',
        'results',
        'status',
        'type',
        'tech_stack',
        'gallery',
        'url',
        'show_on_landing',
        'proxmox_vmid',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'gallery' => 'array',
        'show_on_landing' => 'boolean',
    ];
    public function toSitemapTag(): \Spatie\Sitemap\Tags\Url | string | array
    {
        return \Spatie\Sitemap\Tags\Url::create(route('projects.show', $this))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8);
    }
}
