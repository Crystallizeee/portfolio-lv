<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $personal['name'] }} - CV</title>
    <style>
        @page {
            margin: 40px 55px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.7;
            margin: 0;
            padding: 0;
            font-size: 10.5px;
        }

        table { border-collapse: collapse; width: 100%; }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .name {
            font-size: 30px;
            font-weight: 300;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .professional-title {
            font-size: 10px;
            color: #64748b;
            font-weight: 400;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .contact-row {
            font-size: 9px;
            color: #64748b;
            letter-spacing: 0.5px;
        }

        .contact-separator {
            display: inline-block;
            margin: 0 8px;
            color: #cbd5e1;
        }

        /* ===== SECTIONS ===== */
        .section {
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #0f172a;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ===== SUMMARY ===== */
        .summary-text {
            font-size: 10px;
            color: #475569;
            line-height: 1.8;
            text-align: center;
            max-width: 90%;
            margin: 0 auto 25px auto;
            font-style: italic;
        }

        /* ===== EXPERIENCE ===== */
        .exp-item {
            margin-bottom: 16px;
        }

        .exp-header {
            margin-bottom: 4px;
        }

        .exp-role {
            font-size: 11.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .exp-company {
            font-size: 10px;
            color: #3b82f6;
            font-weight: 500;
        }

        .exp-date {
            font-size: 9px;
            color: #94a3b8;
            font-weight: 500;
        }

        .exp-desc {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.7;
        }

        .exp-desc ul { margin: 4px 0 0 0; padding-left: 16px; }
        .exp-desc li { margin-bottom: 2px; }

        /* ===== SKILLS ===== */
        .skills-grid {
            margin-bottom: 8px;
        }

        .skill-category-label {
            font-size: 8.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .skill-tag {
            display: inline-block;
            color: #334155;
            font-size: 9px;
            font-weight: 500;
            padding: 2px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            margin-right: 4px;
            margin-bottom: 4px;
            background: #f8fafc;
        }

        /* ===== EDUCATION ===== */
        .edu-item {
            margin-bottom: 10px;
        }

        .edu-degree {
            font-size: 10.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .edu-school {
            font-size: 9.5px;
            color: #3b82f6;
            font-weight: 500;
        }

        .edu-year {
            font-size: 8.5px;
            color: #94a3b8;
        }

        /* ===== CERTIFICATIONS ===== */
        .cert-item {
            font-size: 9.5px;
            color: #334155;
            margin-bottom: 4px;
            padding-left: 12px;
            position: relative;
        }

        .cert-item::before {
            content: "—";
            position: absolute;
            left: 0;
            color: #cbd5e1;
        }

        /* ===== LANGUAGES ===== */
        .lang-item {
            display: inline-block;
            font-size: 9.5px;
            color: #475569;
            margin-right: 16px;
            margin-bottom: 4px;
        }

        .lang-name {
            font-weight: 700;
            color: #0f172a;
        }

        /* ===== PROJECTS ===== */
        .project-item {
            margin-bottom: 10px;
        }

        .project-name {
            font-size: 10.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .project-desc {
            font-size: 9px;
            color: #64748b;
            line-height: 1.6;
        }

        /* ===== TWO COLUMN for skills/edu/cert ===== */
        .two-col-table td {
            vertical-align: top;
            padding: 0;
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <div class="header">
        <div class="name">{{ $personal['name'] }}</div>
        <div class="professional-title">{{ $personal['professional_title'] }}</div>
        <div class="contact-row">
            {{ $personal['email'] }}
            @if($personal['phone'])
                <span class="contact-separator">|</span>{{ $personal['phone'] }}
            @endif
            @if($personal['address'])
                <span class="contact-separator">|</span>{{ $personal['address'] }}
            @endif
            @if($personal['linkedin'])
                <span class="contact-separator">|</span>{{ str_replace(['https://', 'http://'], '', $personal['linkedin']) }}
            @endif
            @if($personal['github'])
                <span class="contact-separator">|</span>{{ str_replace(['https://', 'http://'], '', $personal['github']) }}
            @endif
            @if($personal['website'])
                <span class="contact-separator">|</span>{{ str_replace(['https://', 'http://'], '', $personal['website']) }}
            @endif
        </div>
    </div>

    <!-- ===== SUMMARY ===== -->
    @if($personal['summary'])
    <div class="summary-text">{{ $personal['summary'] }}</div>
    @endif

    <!-- ===== EXPERIENCE ===== -->
    @if(count($experiences) > 0)
    <div class="section">
        <div class="section-title">{{ __('cv.work_experience') }}</div>
        @foreach($experiences as $exp)
        <div class="exp-item">
            <table width="100%">
                <tr>
                    <td>
                        <div class="exp-role">{{ $exp['role'] }}</div>
                        <div class="exp-company">{{ $exp['company'] }}</div>
                    </td>
                    <td style="text-align: right; width: 28%; vertical-align: top;">
                        <div class="exp-date">{{ $exp['date_range'] }}</div>
                    </td>
                </tr>
            </table>
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
    </div>
    @endif

    <!-- ===== SKILLS + EDUCATION side by side ===== -->
    <table class="two-col-table" width="100%">
        <tr>
            <td width="58%" style="padding-right: 20px;">
                <!-- Skills -->
                @if(count($skills) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.skills') }}</div>
                    @php $groupedSkills = collect($skills)->groupBy('category'); @endphp
                    @if($groupedSkills->count() > 1)
                        @foreach($groupedSkills as $category => $categorySkills)
                            <div class="skills-grid">
                                <div class="skill-category-label">{{ $category ?: 'Other' }}</div>
                                @foreach($categorySkills as $skill)
                                    <span class="skill-tag">{{ $skill['name'] }}</span>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="skills-grid">
                            @foreach($skills as $skill)
                                <span class="skill-tag">{{ $skill['name'] }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
            </td>
            <td width="42%">
                <!-- Education -->
                @if(count($educations) > 0 && (isset($educations[0]['school']) && $educations[0]['school']))
                <div class="section">
                    <div class="section-title">{{ __('cv.education') }}</div>
                    @foreach($educations as $edu)
                    @if(isset($edu['school']) && $edu['school'])
                    <div class="edu-item">
                        <div class="edu-degree">{{ $edu['degree'] }}</div>
                        <div class="edu-school">{{ $edu['school'] }}</div>
                        <div class="edu-year">{{ $edu['year'] }}</div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif

                <!-- Languages -->
                @if(count($languages) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.languages') }}</div>
                    @foreach($languages as $lang)
                        <div class="lang-item"><span class="lang-name">{{ $lang['name'] }}</span> &ndash; {{ $lang['level'] }}</div>
                    @endforeach
                </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- ===== CERTIFICATIONS ===== -->
    @if(count($certifications) > 0 && (isset($certifications[0]['name']) && $certifications[0]['name']))
    <div class="section">
        <div class="section-title">{{ __('cv.certifications') }}</div>
        @foreach($certifications as $cert)
        @if(isset($cert['name']) && $cert['name'])
        <div class="cert-item">{{ $cert['name'] }}</div>
        @endif
        @endforeach
    </div>
    @endif

    <!-- ===== PROJECTS ===== -->
    @if(count($projects) > 0)
    <div class="section">
        <div class="section-title">{{ __('cv.projects') }}</div>
        @foreach($projects as $project)
        <div class="project-item">
            <div class="project-name">{{ $project['title'] }}</div>
            <div class="project-desc">{{ $project['description'] }}</div>
        </div>
        @endforeach
    </div>
    @endif

</body>
</html>
