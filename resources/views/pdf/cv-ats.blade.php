<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $personal['name'] }} - CV</title>
    {{-- ATS-friendly: single column, no tables, no icons/images, standard headings, real text only. --}}
    <style>
        @page { margin: 45px 55px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #000;
            font-size: 10.5px;
            line-height: 1.45;
            margin: 0;
        }
        h1 { font-size: 20px; margin: 0; font-weight: bold; }
        .title { font-size: 11.5px; margin: 2px 0 4px 0; }
        .contact { font-size: 9.5px; margin-bottom: 6px; }
        h2 {
            font-size: 11.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 14px 0 6px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #000;
        }
        .item { margin-bottom: 8px; }
        .item-head { font-weight: bold; }
        .item-sub { font-style: italic; }
        p { margin: 2px 0; }
        ul { margin: 2px 0 0 0; padding-left: 16px; }
        li { margin-bottom: 1px; }
    </style>
</head>
<body>
    @php
        $clean = fn ($url) => rtrim(str_replace(['https://', 'http://', 'www.'], '', (string) $url), '/');
        $contact = array_filter([
            $personal['email'] ?? null,
            $personal['phone'] ?? null,
            $personal['address'] ?? null,
            !empty($personal['linkedin']) ? $clean($personal['linkedin']) : null,
            !empty($personal['github']) ? $clean($personal['github']) : null,
            !empty($personal['website']) ? $clean($personal['website']) : null,
        ]);
        $educations = array_values(array_filter($educations, fn ($e) => !empty($e['school'])));
        $certifications = array_values(array_filter($certifications, fn ($c) => !empty($c['name'])));
        $bullets = fn ($text) => array_values(array_filter(array_map(
            fn ($l) => ltrim(trim($l), "•-●* "),
            preg_split('/\r?\n/', (string) $text)
        )));
    @endphp

    {{-- HEADER --}}
    <h1>{{ $personal['name'] }}</h1>
    @if(!empty($personal['professional_title']))
        <div class="title">{{ $personal['professional_title'] }}</div>
    @endif
    <div class="contact">{{ implode(' | ', $contact) }}</div>

    {{-- SUMMARY --}}
    @if(!empty($personal['summary']))
        <h2>{{ __('cv.summary') }}</h2>
        <p>{{ $personal['summary'] }}</p>
    @endif

    {{-- EXPERIENCE --}}
    @if(count($experiences) > 0)
        <h2>{{ __('cv.work_experience') }}</h2>
        @foreach($experiences as $exp)
            <div class="item">
                <div class="item-head">{{ $exp['role'] ?? '' }}</div>
                <div class="item-sub">{{ $exp['company'] ?? '' }}@if(!empty($exp['date_range'])) | {{ $exp['date_range'] }}@endif</div>
                @php $lines = $bullets($exp['description'] ?? ''); @endphp
                @if(count($lines) > 1)
                    <ul>@foreach($lines as $line)<li>{{ $line }}</li>@endforeach</ul>
                @elseif(count($lines) === 1)
                    <p>{{ $lines[0] }}</p>
                @endif
            </div>
        @endforeach
    @endif

    {{-- SKILLS --}}
    @if(count($skills) > 0)
        <h2>{{ __('cv.skills') }}</h2>
        @php $grouped = collect($skills)->groupBy(fn ($s) => $s['category'] ?? ''); @endphp
        @if($grouped->count() > 1)
            @foreach($grouped as $category => $items)
                <p><strong>{{ $category ?: 'Other' }}:</strong> {{ $items->pluck('name')->filter()->implode(', ') }}</p>
            @endforeach
        @else
            <p>{{ collect($skills)->pluck('name')->filter()->implode(', ') }}</p>
        @endif
    @endif

    {{-- PROJECTS --}}
    @if(count($projects) > 0)
        <h2>{{ __('cv.projects') }}</h2>
        @foreach($projects as $project)
            <div class="item">
                <div class="item-head">{{ $project['title'] ?? '' }}</div>
                @if(!empty($project['tech_stack']) && is_array($project['tech_stack']))
                    <div class="item-sub">{{ __('cv.tech_stack') }}: {{ implode(', ', $project['tech_stack']) }}</div>
                @endif
                @if(!empty($project['description']))
                    <p>{{ strip_tags($project['description']) }}</p>
                @endif
            </div>
        @endforeach
    @endif

    {{-- EDUCATION --}}
    @if(count($educations) > 0)
        <h2>{{ __('cv.education') }}</h2>
        @foreach($educations as $edu)
            <div class="item">
                <div class="item-head">{{ $edu['degree'] ?? '' }}</div>
                <div class="item-sub">{{ $edu['school'] }}@if(!empty($edu['year'])) | {{ $edu['year'] }}@endif</div>
                @if(!empty($edu['thesis']))
                    <p>{{ __('cv.thesis') }}: {{ $edu['thesis'] }}</p>
                @endif
            </div>
        @endforeach
    @endif

    {{-- CERTIFICATIONS --}}
    @if(count($certifications) > 0)
        <h2>{{ __('cv.certifications') }}</h2>
        <ul>
            @foreach($certifications as $cert)
                <li>{{ $cert['name'] }}@if(!empty($cert['issuer'])) - {{ $cert['issuer'] }}@endif @if(!empty($cert['year']))({{ $cert['year'] }})@endif</li>
            @endforeach
        </ul>
    @endif

    {{-- LANGUAGES --}}
    @if(count($languages) > 0)
        <h2>{{ __('cv.languages') }}</h2>
        <p>{{ collect($languages)->map(fn ($l) => trim(($l['name'] ?? '') . (!empty($l['level']) ? ' (' . $l['level'] . ')' : '')))->filter()->implode(', ') }}</p>
    @endif
</body>
</html>
