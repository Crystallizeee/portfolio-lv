<!DOCTYPE html>
<html lang="{{ $language ?? 'id' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cover Letter - {{ $name }}</title>
    <style>
        @page { margin: 52px 60px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 10.5pt;
            line-height: 1.55;
            margin: 0;
        }
        .header {
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .name {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 3px 0;
        }
        .contact {
            font-size: 9pt;
            color: #475569;
        }
        .meta {
            margin-bottom: 16px;
            font-size: 10pt;
            color: #334155;
        }
        .meta-date { margin-bottom: 8px; }
        .subject {
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 14px;
        }
        .salutation {
            margin-bottom: 12px;
            font-weight: 500;
        }
        .body p {
            margin: 0 0 11px 0;
            text-align: justify;
        }
        .closing-block {
            margin-top: 20px;
        }
        .signature-img {
            height: 58px;
            width: auto;
            margin: 8px 0 4px 0;
            display: block;
        }
        .signature-spacer {
            height: 46px;
        }
        .signer-name {
            font-weight: bold;
            color: #0f172a;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    @php
        $clean = fn ($url) => rtrim(str_replace(['https://', 'http://', 'www.'], '', (string) $url), '/');
        $contacts = array_filter([
            $email ?? null,
            $phone ?? null,
            $address ?? null,
            !empty($linkedin) ? $clean($linkedin) : null,
            !empty($website) ? $clean($website) : null,
        ]);
        $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\r?\n\s*\r?\n/', (string) $body))));
        if (count($paragraphs) <= 1) {
            $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $body))));
        }
    @endphp

    <div class="header">
        <div class="name">{{ $name }}</div>
        @if(!empty($contacts))
            <div class="contact">{{ implode(' | ', $contacts) }}</div>
        @endif
    </div>

    <div class="meta">
        <div class="meta-date">{{ $date }}</div>
        @if(!empty($companyName))
            <div><strong>{{ $companyName }}</strong></div>
        @endif
    </div>

    @if(!empty($jobTitle))
        <div class="subject">
            {{ ($language ?? 'id') === 'en' ? 'Application for ' . $jobTitle : 'Lamaran Pekerjaan: ' . $jobTitle }}
        </div>
    @endif

    <div class="body">
        @foreach($paragraphs as $p)
            <p>{{ $p }}</p>
        @endforeach
    </div>

    <div class="closing-block">
        <div>{{ ($language ?? 'id') === 'en' ? 'Sincerely,' : 'Hormat saya,' }}</div>
        @if(!empty($signatureDataUri))
            <img src="{{ $signatureDataUri }}" alt="Signature" class="signature-img">
        @else
            <div class="signature-spacer"></div>
        @endif
        <div class="signer-name">{{ $name }}</div>
    </div>
</body>
</html>
