@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $owner?->name ?? 'Benidictus Tri Wibowo',
    'url' => config('app.url'),
    'jobTitle' => 'Cybersecurity & ICT Risk Professional',
    'description' => $owner?->summary ?? 'Hybrid GRC & Technical Practitioner specializing in ISO 27001 and Offensive Security',
    'email' => $owner?->email ?? '',
    'sameAs' => array_values(array_filter([
        $owner?->linkedin,
        $owner?->github,
        $owner?->website,
    ])),
    'knowsAbout' => [
        'ISO 27001',
        'Cybersecurity',
        'Penetration Testing',
        'GRC',
        'ICT Risk Assessment',
        'SIEM',
        'Laravel',
        'Infrastructure Automation',
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
