@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'SoftwareSourceCode',
    'name' => $project->title,
    'description' => $project->seo?->description ?? $project->description,
    'url' => route('projects.show', $project),
    'dateModified' => $project->updated_at->toIso8601String(),
    'programmingLanguage' => $project->tech_stack ?? [],
    'author' => [
        '@type' => 'Person',
        'name' => 'Benidictus Tri Wibowo',
        'url' => config('app.url'),
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('projects.show', $project),
    ],
] + ($project->url ? ['codeRepository' => $project->url] : []),
JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
