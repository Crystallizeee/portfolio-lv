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
            -webkit-font-smoothing: antialiased;
        }

        /* ===== HEADER ===== */
        .header {
            background-color: #0f172a;
            color: #ffffff;
            padding: 35px 45px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: bottom;
            width: 60%;
        }

        .header-right {
            display: table-cell;
            vertical-align: bottom;
            text-align: right;
            width: 40%;
        }

        .name {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4px;
            color: #ffffff;
        }

        .title-line {
            font-size: 13px;
            color: #60a5fa;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .contact-item {
            font-size: 10px;
            color: #cbd5e1;
            margin-bottom: 4px;
        }

        .contact-icon {
            color: #60a5fa;
            margin-right: 6px;
        }

        .contact-link {
            color: #cbd5e1;
            text-decoration: none;
        }

        /* ===== BODY ===== */
        .body-content {
            padding: 30px 45px;
        }

        /* ===== SUMMARY ===== */
        .summary {
            font-size: 12px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ===== TWO COLUMN LAYOUT ===== */
        .two-col {
            display: table;
            width: 100%;
        }

        .main-col {
            display: table-cell;
            width: 62%;
            padding-right: 30px;
            vertical-align: top;
        }

        .side-col {
            display: table-cell;
            width: 38%;
            vertical-align: top;
            border-left: 1px solid #e2e8f0;
            padding-left: 25px;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #334e68;
            margin-bottom: 15px;
            padding-bottom: 6px;
            border-bottom: 2px solid #2563eb;
            display: inline-block;
        }

        /* ===== SECTION ===== */
        .section {
            margin-bottom: 22px;
        }

        /* ===== EXPERIENCE ===== */
        .exp-item {
            margin-bottom: 18px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .exp-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .exp-header {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .exp-role {
            display: table-cell;
            font-size: 13px;
            font-weight: bold;
            color: #102a43;
        }

        .exp-date {
            display: table-cell;
            text-align: right;
            font-size: 10px;
            font-weight: 500;
            color: #64748b;
        }

        .exp-company {
            font-size: 11px;
            color: #486581;
            font-style: italic;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .exp-desc {
            font-size: 10.5px;
            color: #475569;
            line-height: 1.6;
        }

        .exp-desc ul {
            margin: 0;
            padding-left: 14px;
        }

        .exp-desc li {
            margin-bottom: 3px;
        }

        /* ===== PROJECTS ===== */
        .project-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }

        .project-name {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .project-desc {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.5;
        }

        /* ===== SIDEBAR ITEMS ===== */
        .skill-category-title {
            font-size: 9px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 6px;
            margin-top: 10px;
        }

        .skill-category-title:first-child {
            margin-top: 0;
        }

        .skill-tag {
            display: inline-block;
            background-color: #f1f5f9;
            color: #334e68;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9.5px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            margin-right: 3px;
            margin-bottom: 4px;
        }

        /* Education */
        .edu-degree {
            font-size: 11px;
            font-weight: bold;
            color: #102a43;
        }

        .edu-school {
            font-size: 10px;
            color: #486581;
        }

        .edu-year {
            font-size: 9px;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 2px;
        }

        .edu-thesis {
            font-size: 9px;
            font-style: italic;
            color: #64748b;
            margin-top: 3px;
        }

        /* Certifications */
        .cert-item {
            margin-bottom: 6px;
            padding-left: 12px;
            position: relative;
        }

        .cert-bullet {
            display: inline-block;
            width: 5px;
            height: 5px;
            background-color: #2563eb;
            border-radius: 50%;
            position: absolute;
            left: 0;
            top: 5px;
        }

        .cert-name {
            font-size: 10px;
            color: #475569;
        }

        /* Languages */
        .lang-item {
            display: inline-block;
            background-color: #f1f5f9;
            color: #334e68;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9.5px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            margin-right: 3px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="name">{{ $personal['name'] }}</div>
                @if($personal['summary'])
                    <div class="title-line">ICT Security Professional & Software Engineer</div>
                @endif
            </div>
            <div class="header-right">
                @if($personal['phone'])
                    <div class="contact-item">
                        <span class="contact-icon">&#9742;</span> {{ $personal['phone'] }}
                    </div>
                @endif
                <div class="contact-item">
                    <span class="contact-icon">&#9993;</span> {{ $personal['email'] }}
                </div>
                @if($personal['linkedin'])
                    <div class="contact-item">
                        <span class="contact-icon">&#128279;</span> <a href="{{ $personal['linkedin'] }}" class="contact-link">{{ str_replace(['https://', 'http://'], '', $personal['linkedin']) }}</a>
                    </div>
                @endif
                @if($personal['website'])
                    <div class="contact-item">
                        <span class="contact-icon">&#127760;</span> <a href="{{ $personal['website'] }}" class="contact-link">{{ str_replace(['https://', 'http://'], '', $personal['website']) }}</a>
                    </div>
                @endif
                @if($personal['github'])
                    <div class="contact-item">
                        <span class="contact-icon">&#128187;</span> <a href="{{ $personal['github'] }}" class="contact-link">{{ str_replace(['https://', 'http://'], '', $personal['github']) }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="body-content">

        <!-- ===== SUMMARY ===== -->
        @if($personal['summary'])
        <div class="summary">
            {{ $personal['summary'] }}
        </div>
        @endif

        <!-- ===== TWO COLUMN LAYOUT ===== -->
        <div class="two-col">

            <!-- ===== MAIN COLUMN ===== -->
            <div class="main-col">

                <!-- Experience -->
                @if(count($experiences) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.work_experience') }}</div>
                    @foreach($experiences as $exp)
                    <div class="exp-item">
                        <div class="exp-header">
                            <div class="exp-role">{{ $exp['role'] }}</div>
                            <div class="exp-date">{{ $exp['date_range'] }}</div>
                        </div>
                        <div class="exp-company">{{ $exp['company'] }}</div>
                        <div class="exp-desc">
                            @php
                                $lines = array_filter(explode("\n", $exp['description']));
                            @endphp
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

            <!-- ===== SIDEBAR ===== -->
            <div class="side-col">

                <!-- Skills -->
                @if(count($skills) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.skills') }}</div>
                    @php
                        $groupedSkills = collect($skills)->groupBy('category');
                    @endphp
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
                    <div style="margin-bottom: 10px;">
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
                        <span class="cert-bullet"></span>
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
        </div>
    </div>

</body>
</html>
