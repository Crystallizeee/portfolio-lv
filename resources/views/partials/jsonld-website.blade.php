<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'Benidictus Tri Wibowo',
    'url' => config('app.url'),
    'description' => 'Hybrid GRC & Technical Practitioner specializing in ISO 27001 and Offensive Security',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => config('app.url') . '/blog?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
