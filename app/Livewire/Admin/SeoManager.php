<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SeoMetadata;
use Livewire\Attributes\Layout;

class SeoManager extends Component
{
    public $title;
    public $description;
    public $keywords;
    public $og_image;
    public $canonical_url;
    public $indexable = true;

    public function mount()
    {
        // specific 'global' record
        $seo = SeoMetadata::where('model_type', 'global')->first();

        if ($seo) {
            $this->title = $seo->title;
            $this->description = $seo->description;
            $this->keywords = $seo->keywords;
            $this->og_image = $seo->og_image;
            $this->canonical_url = $seo->canonical_url;
            $this->indexable = $seo->indexable;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'og_image' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'indexable' => 'boolean',
        ]);

        SeoMetadata::updateOrCreate(
            ['model_type' => 'global', 'model_id' => 0], // 0 for global singleton
            [
                'title' => $this->title,
                'description' => $this->description,
                'keywords' => $this->keywords,
                'og_image' => $this->og_image,
                'canonical_url' => $this->canonical_url,
                'indexable' => $this->indexable,
            ]
        );

        session()->flash('message', 'Global SEO settings updated successfully.');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.seo-manager', [
            'title' => 'Global SEO Settings'
        ]);
    }
}
