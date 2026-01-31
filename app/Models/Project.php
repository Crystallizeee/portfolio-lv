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
        'description',
        'status',
        'type',
        'tech_stack',
        'url',
    ];

    protected $casts = [
        'tech_stack' => 'array',
    ];
    public function toSitemapTag(): \Spatie\Sitemap\Tags\Url | string | array
    {
        // Assuming there is a route 'projects.show', otherwise point to a hash or similar
        // Since we don't have a dedicated project detail page yet, we might point to the modal URL if it existed,
        // but for now let's assume we want them indexed if we had a route.
        // If no route exists, we can return empty to skip, OR if we strictly want them in sitemap
        // we need a URL. 
        // NOTE: The user's portfolio seems to be a Single Page App style for now with modals.
        // If there are no individual routes, sitemap for projects is tricky.
        // However, standard SEO usually requires distinct URLs.
        // Let's check routes first. If no routes, we can't really sitemap them effectively.
        
        // For now, I will use a placeholder or the main URL with a hash, e.g. /#project-{id}
        // effectively linking to the homepage anchor.
        return \Spatie\Sitemap\Tags\Url::create(url('/#project-' . $this->id))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8);
    }
}
