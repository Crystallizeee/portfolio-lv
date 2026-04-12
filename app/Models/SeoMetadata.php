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
        'schema_markup',
        'og_type',
    ];

    protected $casts = [
        'indexable' => 'boolean',
        'schema_markup' => 'array',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Render JSON-LD structured data as a script tag for the <head>.
     *
     * @return string|null
     */
    public function getJsonLd(): ?string
    {
        if (empty($this->schema_markup)) {
            return null;
        }

        $json = json_encode($this->schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return '<script type="application/ld+json">' . $json . '</script>';
    }
}
