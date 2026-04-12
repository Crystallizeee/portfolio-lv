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

    /**
     * Get the JSON-LD structured data for this model.
     * Returns the custom schema_markup from SEO metadata if set,
     * otherwise auto-generates based on the model type.
     *
     * @return string|null Rendered <script type="application/ld+json"> tag
     */
    public function getSeoJsonLdAttribute(): ?string
    {
        // Use custom schema markup if set
        if ($this->seo?->schema_markup) {
            return $this->seo->getJsonLd();
        }

        // Auto-generate based on model type
        $schema = $this->generateSchemaMarkup();
        if (empty($schema)) {
            return null;
        }

        $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return '<script type="application/ld+json">' . $json . '</script>';
    }

    /**
     * Auto-generate Schema.org markup based on the model.
     * Override in specific models for custom schema generation.
     *
     * @return array|null
     */
    protected function generateSchemaMarkup(): ?array
    {
        $className = class_basename($this);

        return match ($className) {
            'Project' => $this->generateProjectSchema(),
            default => null,
        };
    }

    /**
     * Generate SoftwareSourceCode / CreativeWork schema for projects.
     */
    protected function generateProjectSchema(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareSourceCode',
            'name' => $this->title,
            'description' => $this->seo_description,
            'url' => route('projects.show', $this),
            'author' => [
                '@type' => 'Person',
                'name' => 'Benidictus Tri Wibowo',
                'url' => config('app.url'),
            ],
            'dateModified' => $this->updated_at?->toIso8601String(),
        ];

        // Add programming languages from tech_stack
        if (!empty($this->tech_stack) && is_array($this->tech_stack)) {
            $schema['programmingLanguage'] = $this->tech_stack;
        }

        // Add live URL if available
        if ($this->url) {
            $schema['codeRepository'] = $this->url;
        }

        return $schema;
    }
}
