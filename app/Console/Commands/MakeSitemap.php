<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Project;
use App\Models\Post;

class MakeSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap with all public pages.';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create()
            // Homepage
            ->add(Url::create('/')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(1.0))
            // Blog index
            ->add(Url::create('/blog')
                ->setLastModificationDate(Post::published()->latest('published_at')->first()?->published_at ?? now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9));

        // Blog posts
        Post::published()
            ->orderBy('published_at', 'desc')
            ->get()
            ->each(function ($post) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('blog.show', $post->slug))
                        ->setLastModificationDate($post->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            });

        // Projects
        Project::all()->each(function ($project) use ($sitemap) {
            $sitemap->add($project->toSitemapTag());
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully! Includes:');
        $this->info('  - Homepage');
        $this->info('  - Blog index');
        $this->info('  - ' . Post::published()->count() . ' blog posts');
        $this->info('  - ' . Project::count() . ' projects');
    }
}
