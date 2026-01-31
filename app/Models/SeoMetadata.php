<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMetadata extends Model
{
    protected $fillable = [
        'title',
        'description',
        'keywords',
        'og_image',
        'indexable',
        'canonical_url',
    ];

    protected $casts = [
        'indexable' => 'boolean',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
