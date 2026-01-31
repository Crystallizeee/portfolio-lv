<?php

namespace App\Traits;

use App\Models\SeoMetadata;

trait HasSeo
{
    public function seo()
    {
        return $this->morphOne(SeoMetadata::class, 'model');
    }

    public function getSeoTitleAttribute()
    {
        return $this->seo?->title ?? $this->title ?? $this->name ?? config('app.name');
    }

    public function getSeoDescriptionAttribute()
    {
        return $this->seo?->description ?? $this->description ?? $this->summary ?? '';
    }

    public function getSeoImageAttribute()
    {
        return $this->seo?->og_image ?? $this->image ?? $this->thumbnail ?? null;
    }
}
