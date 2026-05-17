<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $personal['name'] }} - CV</title>
    <style>
        @page {
            margin: 35px 40px 35px 40px;
        }

        body {
            width: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            font-size: 9.5px;
        }

        table { border-collapse: collapse; width: 100%; }

        /* ===== HEADER ===== */
        .header {
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 3px solid #0f172a;
        }

        .header-table td { vertical-align: top; }

        .name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            margin-bottom: 1px;
        }

        .professional-title {
            font-size: 9px;
            color: #2563eb;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .contact-item {
            font-size: 8px;
            color: #475569;
            margin-bottom: 1px;
        }

        /* ===== SUMMARY ===== */
        .summary-box {
            background-color: #f1f5f9;
            padding: 7px 10px;
            margin-bottom: 10px;
            font-size: 8.5px;
            color: #334155;
            font-style: italic;
            line-height: 1.45;
        }

        /* ===== SECTION TITLES ===== */
        .section-title {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #0f172a;
            margin-bottom: 6px;
            margin-top: 2px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ===== EXPERIENCE ===== */
        .exp-item {
            margin-bottom: 8px;
        }

        .exp-header td { vertical-align: top; }

        .exp-role {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
        }

        .exp-date {
            font-size: 7.5px;
            font-weight: 600;
            color: #64748b;
            text-align: right;
        }

        .exp-company {
            font-size: 8.5px;
            color: #2563eb;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .exp-desc {
            font-size: 8px;
            color: #334155;
            line-height: 1.45;
        }

        .exp-desc ul { margin: 2px 0 0 0; padding-left: 12px; }
        .exp-desc li { margin-bottom: 1px; }

        /* ===== SKILLS (inline) ===== */
        .skill-cat-label {
            font-size: 7.5px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .skill-list {
            font-size: 8px;
            color: #334155;
        }

        .skills-row {
            margin-bottom: 4px;
        }

        /* ===== EDUCATION / CERT / LANG ===== */
        .edu-degree { font-size: 9px; font-weight: bold; color: #0f172a; }
        .edu-school { font-size: 8px; color: #2563eb; font-weight: 500; }
        .edu-year { font-size: 7.5px; color: #64748b; }

        .cert-item {
            font-size: 8px;
            color: #334155;
            margin-bottom: 2px;
        }
        .cert-bullet { color: #2563eb; margin-right: 4px; }

        .lang-item {
            font-size: 8px;
            color: #475569;
        }
        .lang-name { font-weight: bold; color: #0f172a; }

        /* ===== PROJECTS ===== */
        .project-item {
            margin-bottom: 5px;
            padding: 4px 8px;
            background-color: #f8fafc;
            border-left: 3px solid #2563eb;
        }

        .project-name {
            font-size: 9px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 1px;
        }

        .project-desc {
            font-size: 7.5px;
            color: #475569;
            line-height: 1.4;
        }

        /* ===== DIVIDER ===== */
        .section-divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 8px 0;
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <div class="header">
        <table class="header-table" width="100%">
            <tr>
                <td width="55%">
                    <div class="name">{{ $personal['name'] }}</div>
                    <div class="professional-title">{{ $personal['professional_title'] }}</div>
                </td>
                <td width="45%" style="text-align: right;">
                    @if($personal['address'])<div class="contact-item">{{ $personal['address'] }}</div>@endif
                    @if($personal['phone'])<div class="contact-item">{{ $personal['phone'] }}</div>@endif
                    <div class="contact-item">{{ $personal['email'] }}</div>
                    @if($personal['linkedin'])<div class="contact-item">{{ str_replace(['https://', 'http://'], '', $personal['linkedin']) }}</div>@endif
                    @if($personal['website'])<div class="contact-item">{{ str_replace(['https://', 'http://'], '', $personal['website']) }}</div>@endif
                    @if($personal['github'])<div class="contact-item">{{ str_replace(['https://', 'http://'], '', $personal['github']) }}</div>@endif
                </td>
            </tr>
        </table>
    </div>

    <!-- ===== SUMMARY ===== -->
    @if($personal['summary'])
    <div class="summary-box">{{ $personal['summary'] }}</div>
    @endif

    <!-- ===== EXPERIENCE ===== -->
    @if(count($experiences) > 0)
    <div class="section-title">{{ __("cv.work_experience") }}</div>
    @foreach($experiences as $exp)
    <div class="exp-item">
        <table class="exp-header" width="100%">
            <tr>
                <td><span class="exp-role">{{ $exp['role'] }}</span></td>
                <td style="width: 25%;"><span class="exp-date">{{ $exp['date_range'] }}</span></td>
            </tr>
        </table>
        <div class="exp-company">{{ $exp['company'] }}</div>
        <div class="exp-desc">
            @php $lines = array_filter(explode("\n", $exp['description'])); @endphp
            @if(count($lines) > 1)
                <ul>
                    @foreach($lines as $line)
                        @if(trim($line))
                            <li>{{ ltrim(trim($line), '•-● ') }}</li>
                        @endif
                    @endforeach
                </ul>
            @else
                {{ $exp['description'] }}
            @endif
        </div>
    </div>
    @endforeach
    @endif

    <hr class="section-divider">

    <!-- ===== SKILLS (compact inline format) ===== -->
    @if(count($skills) > 0)
    <div class="section-title">{{ __('cv.skills') }}</div>
    @php $groupedSkills = collect($skills)->groupBy('category'); @endphp
    @foreach($groupedSkills as $category => $categorySkills)
    <div class="skills-row">
        <span class="skill-cat-label">{{ $category ?: 'Other' }}:</span>
        <span class="skill-list">{{ $categorySkills->pluck('name')->join(' · ') }}</span>
    </div>
    @endforeach
    @endif

    <hr class="section-divider">

    <!-- ===== EDUCATION + CERTIFICATIONS + LANGUAGES ===== -->
    <table width="100%">
        <tr>
            <td width="35%" style="vertical-align: top; padding-right: 10px;">
                @if(count($educations) > 0 && (isset($educations[0]['school']) && $educations[0]['school']))
                <div class="section-title">{{ __('cv.education') }}</div>
                @foreach($educations as $edu)
                    @if(isset($edu['school']) && $edu['school'])
                    <div style="margin-bottom: 4px;">
                        <div class="edu-degree">{{ $edu['degree'] }}</div>
                        <div class="edu-school">{{ $edu['school'] }}</div>
                        <div class="edu-year">{{ $edu['year'] }}</div>
                    </div>
                    @endif
                @endforeach
                @endif

                @if(count($languages) > 0)
                <div class="section-title" style="margin-top: 8px;">{{ __('cv.languages') }}</div>
                @foreach($languages as $lang)
                    <div class="lang-item"><span class="lang-name">{{ $lang['name'] }}</span> &ndash; {{ $lang['level'] }}</div>
                @endforeach
                @endif
            </td>
            <td width="65%" style="vertical-align: top;">
                @if(count($certifications) > 0 && (isset($certifications[0]['name']) && $certifications[0]['name']))
                <div class="section-title">{{ __('cv.certifications') }}</div>
                @foreach($certifications as $cert)
                    @if(isset($cert['name']) && $cert['name'])
                    <div class="cert-item">
                        <span class="cert-bullet">&#9679;</span>
                        {{ $cert['name'] }}@if(isset($cert['issuer']) && $cert['issuer']) — {{ $cert['issuer'] }}@endif
                    </div>
                    @endif
                @endforeach
                @endif
            </td>
        </tr>
    </table>

    <!-- ===== PROJECTS ===== -->
    @if(count($projects) > 0)
    <hr class="section-divider">
    <div class="section-title">{{ __("cv.projects") }}</div>
    <table width="100%">
        @foreach(collect($projects)->chunk(2) as $chunk)
        <tr>
            @foreach($chunk as $project)
            <td width="50%" style="vertical-align: top; padding-right: 8px; padding-bottom: 5px;">
                <div class="project-item">
                    <div class="project-name">{{ $project['title'] }}</div>
                    <div class="project-desc">{{ $project['description'] }}</div>
                </div>
            </td>
            @endforeach
            @if($chunk->count() === 1)
            <td width="50%"></td>
            @endif
        </tr>
        @endforeach
    </table>
    @endif

</body>
</html>
