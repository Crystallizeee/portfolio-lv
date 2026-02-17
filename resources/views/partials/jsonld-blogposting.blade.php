@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->excerpt ?? Str::limit(strip_tags(Str::markdown($post->content)), 160),
    'url' => route('blog.show', $post->slug),
    'datePublished' => $post->published_at?->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'image' => $post->featured_image,
    'author' => [
        '@type' => 'Person',
        'name' => $post->user->name ?? 'Benidictus Tri Wibowo',
        'url' => config('app.url'),
    ],
    'publisher' => [
        '@type' => 'Person',
        'name' => 'Benidictus Tri Wibowo',
        'url' => config('app.url'),
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('blog.show', $post->slug),
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
