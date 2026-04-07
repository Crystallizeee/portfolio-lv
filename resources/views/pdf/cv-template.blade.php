<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $personal['name'] }} - CV</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        table { border-collapse: collapse; }

        /* ===== HEADER ===== */
        .header {
            background-color: #0f172a;
            color: #ffffff;
            padding: 28px 40px;
        }

        .name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffffff;
            margin-bottom: 3px;
        }

        .title-line {
            font-size: 11px;
            color: #60a5fa;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .contact-item {
            font-size: 9.5px;
            color: #cbd5e1;
            margin-bottom: 2px;
            text-align: right;
        }

        .contact-icon { color: #60a5fa; margin-right: 4px; }

        /* ===== BODY ===== */
        .body-content {
            padding: 20px 40px;
        }

        .summary {
            font-size: 10.5px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ===== FLOAT COLUMNS ===== */
        .main-col {
            width: 58%;
            float: left;
            padding-right: 20px;
        }

        .side-col {
            width: 35%;
            float: right;
        }

        .clearfix::after {
            content: "";
            display: block;
            clear: both;
        }

        /* ===== SECTION ===== */
        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #334e68;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 2px solid #2563eb;
        }

        .section { margin-bottom: 14px; }

        /* ===== EXPERIENCE ===== */
        .exp-item {
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .exp-role {
            font-size: 11.5px;
            font-weight: bold;
            color: #102a43;
        }

        .exp-date {
            font-size: 9px;
            font-weight: 500;
            color: #64748b;
            text-align: right;
        }

        .exp-company {
            font-size: 10px;
            color: #486581;
            font-style: italic;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .exp-desc {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.6;
        }

        .exp-desc ul { margin: 0; padding-left: 14px; }
        .exp-desc li { margin-bottom: 2px; }

        /* ===== PROJECTS ===== */
        .project-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 7px 10px;
            margin-bottom: 5px;
        }

        .project-name {
            font-size: 10px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .project-desc {
            font-size: 8.5px;
            color: #475569;
            line-height: 1.4;
        }

        /* ===== SIDEBAR ===== */
        .skill-category-title {
            font-size: 8px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
            margin-top: 6px;
        }

        .skill-tag {
            display: inline-block;
            background-color: #f1f5f9;
            color: #334e68;
            padding: 1px 5px;
            font-size: 8.5px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            margin-right: 2px;
            margin-bottom: 3px;
        }

        .edu-degree { font-size: 10px; font-weight: bold; color: #102a43; }
        .edu-school { font-size: 9px; color: #486581; }
        .edu-year { font-size: 8px; color: #94a3b8; font-weight: 500; margin-top: 1px; }
        .edu-thesis { font-size: 8px; font-style: italic; color: #64748b; margin-top: 2px; }

        .cert-item { margin-bottom: 3px; }
        .cert-bullet { color: #2563eb; margin-right: 3px; font-size: 7px; }
        .cert-name { font-size: 9px; color: #475569; }

        .lang-item {
            display: inline-block;
            background-color: #f1f5f9;
            color: #334e68;
            padding: 1px 5px;
            font-size: 8.5px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            margin-right: 2px;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <div class="header">
        <table width="100%">
            <tr>
                <td width="55%" style="vertical-align: bottom;">
                    <div class="name">{{ $personal['name'] }}</div>
                    <div class="title-line">{{ $personal['professional_title'] }}</div>
                </td>
                <td width="45%" style="vertical-align: bottom;">
                    @if($personal['phone'])
                        <div class="contact-item"><span class="contact-icon">T</span> {{ $personal['phone'] }}</div>
                    @endif
                    <div class="contact-item"><span class="contact-icon">@</span> {{ $personal['email'] }}</div>
                    @if($personal['linkedin'])
                        <div class="contact-item"><span class="contact-icon">in</span> {{ str_replace(['https://', 'http://'], '', $personal['linkedin']) }}</div>
                    @endif
                    @if($personal['website'])
                        <div class="contact-item"><span class="contact-icon">W</span> {{ str_replace(['https://', 'http://'], '', $personal['website']) }}</div>
                    @endif
                    @if($personal['github'])
                        <div class="contact-item"><span class="contact-icon">G</span> {{ str_replace(['https://', 'http://'], '', $personal['github']) }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="body-content">

        <!-- ===== SUMMARY ===== -->
        @if($personal['summary'])
        <div class="summary">{{ $personal['summary'] }}</div>
        @endif

        <!-- ===== TWO COLUMN LAYOUT (Float-based) ===== -->
        <div class="clearfix">

            <!-- ===== SIDEBAR (Right - rendered first for float) ===== -->
            <div class="side-col">

                <!-- Skills -->
                @if(count($skills) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.skills') }}</div>
                    @php $groupedSkills = collect($skills)->groupBy('category'); @endphp
                    @if($groupedSkills->count() > 1)
                        @foreach($groupedSkills as $category => $categorySkills)
                            <div class="skill-category-title">{{ $category ?: 'Other' }}</div>
                            @foreach($categorySkills as $skill)
                                <span class="skill-tag">{{ $skill['name'] }}</span>
                            @endforeach
                        @endforeach
                    @else
                        @foreach($skills as $skill)
                            <span class="skill-tag">{{ $skill['name'] }}</span>
                        @endforeach
                    @endif
                </div>
                @endif

                <!-- Education -->
                @if(count($educations) > 0 && (isset($educations[0]['school']) && $educations[0]['school']))
                <div class="section">
                    <div class="section-title">{{ __('cv.education') }}</div>
                    @foreach($educations as $edu)
                    @if(isset($edu['school']) && $edu['school'])
                    <div style="margin-bottom: 6px;">
                        <div class="edu-degree">{{ $edu['degree'] }}</div>
                        <div class="edu-school">{{ $edu['school'] }}</div>
                        <div class="edu-year">{{ $edu['year'] }}</div>
                        @if(!empty($edu['thesis']))
                            <div class="edu-thesis">{{ __('cv.thesis') }}: {{ $edu['thesis'] }}</div>
                        @endif
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif

                <!-- Certifications -->
                @if(count($certifications) > 0 && (isset($certifications[0]['name']) && $certifications[0]['name']))
                <div class="section">
                    <div class="section-title">{{ __('cv.certifications') }}</div>
                    @foreach($certifications as $cert)
                    @if(isset($cert['name']) && $cert['name'])
                    <div class="cert-item">
                        <span class="cert-bullet">&#9679;</span>
                        <span class="cert-name">{{ $cert['name'] }}@if(!empty($cert['issuer'])) &ndash; {{ $cert['issuer'] }}@endif</span>
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
                        <span class="lang-item">{{ $lang['name'] }} ({{ $lang['level'] }})</span>
                    @endforeach
                </div>
                @endif

            </div>

            <!-- ===== MAIN COLUMN (Left) ===== -->
            <div class="main-col">

                <!-- Experience -->
                @if(count($experiences) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.work_experience') }}</div>
                    @foreach($experiences as $exp)
                    <div class="exp-item">
                        <table width="100%">
                            <tr>
                                <td><span class="exp-role">{{ $exp['role'] }}</span></td>
                                <td class="exp-date">{{ $exp['date_range'] }}</td>
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
                </div>
                @endif

                <!-- Projects -->
                @if(count($projects) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.projects') }}</div>
                    @foreach($projects as $project)
                    <div class="project-card">
                        <div class="project-name">{{ $project['title'] }}</div>
                        <div class="project-desc">{{ $project['description'] }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>

        </div>
    </div>

</body>
</html>
