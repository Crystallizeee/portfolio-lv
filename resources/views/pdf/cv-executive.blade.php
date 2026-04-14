<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $personal['name'] }} - CV</title>
    <style>
        @page {
            margin: 45px 50px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        table { border-collapse: collapse; width: 100%; }

        /* ===== HEADER ===== */
        .header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1e3a5f;
            background-color: #1e3a5f;
            padding: 18px 20px 14px 20px;
            margin: -45px -50px 20px -50px;
        }

        .name {
            font-size: 26px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .professional-title {
            font-size: 11px;
            color: #d4a843;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .contact-item {
            font-size: 9.5px;
            color: #94b8db;
            margin-bottom: 3px;
        }

        .contact-icon { 
            display: inline-block;
            vertical-align: middle;
            margin-right: 6px;
            width: 12px;
            height: 12px;
        }

        /* ===== BODY ===== */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1e3a5f;
            margin-bottom: 12px;
            padding-bottom: 4px;
            border-bottom: 2px solid #1e3a5f;
        }

        .section { margin-bottom: 20px; }

        /* ===== TWO COLUMN LAYOUT (Float-based) ===== */
        .main-col {
            width: 63%;
            float: left;
            padding-right: 25px;
        }

        .side-col {
            width: 33%;
            float: right;
        }

        .clearfix::after {
            content: "";
            display: block;
            clear: both;
        }

        /* ===== EXPERIENCE ===== */
        .exp-item {
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 2px solid #e2e8f0;
        }

        .exp-role {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }

        .exp-company {
            font-size: 10px;
            color: #1e3a5f;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .exp-date {
            font-size: 9px;
            font-weight: 600;
            color: #d4a843;
            text-align: right;
        }

        .exp-desc {
            font-size: 9.5px;
            color: #334155;
            line-height: 1.6;
        }

        .exp-desc ul { margin: 5px 0 0 0; padding-left: 15px; }
        .exp-desc li { margin-bottom: 3px; }

        /* ===== PROJECTS ===== */
        .project-item {
            margin-bottom: 12px;
            padding: 10px;
            background-color: #f8fafc;
            border-left: 3px solid #d4a843;
        }

        .project-name {
            font-size: 10.5px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 3px;
        }

        .project-desc {
            font-size: 9px;
            color: #475569;
            line-height: 1.5;
        }

        /* ===== SUMMARY ===== */
        .summary-box {
            background-color: #f0f4f8;
            border-left: 4px solid #d4a843;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 10px;
            color: #334155;
            font-style: italic;
        }

        /* ===== SIDEBAR ===== */
        .sidebar-section-title {
            font-size: 9px;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            border-left: 3px solid #d4a843;
            padding-left: 8px;
        }

        .skill-group { margin-bottom: 15px; }
        .skill-category { font-size: 8.5px; font-weight: bold; color: #475569; margin-bottom: 5px; }

        .skill-tag {
            display: inline-block;
            background-color: #1e3a5f;
            color: #ffffff;
            padding: 2px 8px;
            font-size: 8.5px;
            font-weight: 600;
            border-radius: 3px;
            margin-right: 4px;
            margin-bottom: 5px;
        }

        .edu-item { margin-bottom: 10px; }
        .edu-degree { font-size: 10px; font-weight: bold; color: #0f172a; }
        .edu-school { font-size: 9px; color: #1e3a5f; font-weight: 500; }
        .edu-year { font-size: 8.5px; color: #64748b; }

        .cert-item { margin-bottom: 6px; font-size: 9px; color: #334155; padding-left: 8px; border-left: 2px solid #d4a843; }

        .lang-tag {
            display: block;
            font-size: 9px;
            color: #475569;
            margin-bottom: 4px;
        }
        .lang-name { font-weight: bold; color: #1e3a5f; }

    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <div class="header">
        <table width="100%">
            <tr>
                <td width="55%" style="vertical-align: top;">
                    <div class="name">{{ $personal['name'] }}</div>
                    <div class="professional-title">{{ $personal['professional_title'] }}</div>
                </td>
                <td width="45%" style="vertical-align: top; text-align: right;">
                    @if($personal['address'])
                        <div class="contact-item">
                            {{ $personal['address'] }}
                        </div>
                    @endif
                    @if($personal['phone'])
                        <div class="contact-item">
                            {{ $personal['phone'] }}
                        </div>
                    @endif
                    <div class="contact-item">
                        {{ $personal['email'] }}
                    </div>
                    @if($personal['linkedin'])
                        <div class="contact-item">
                            {{ str_replace(['https://', 'http://'], '', $personal['linkedin']) }}
                        </div>
                    @endif
                    @if($personal['website'])
                        <div class="contact-item">
                            {{ str_replace(['https://', 'http://'], '', $personal['website']) }}
                        </div>
                    @endif
                    @if($personal['github'])
                        <div class="contact-item">
                            {{ str_replace(['https://', 'http://'], '', $personal['github']) }}
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="body-content">
        
        <!-- ===== SUMMARY ===== -->
        @if($personal['summary'])
        <div class="summary-box">{{ $personal['summary'] }}</div>
        @endif

        <div class="clearfix">
            
            <!-- ===== SIDEBAR ===== -->
            <div class="side-col">
                
                <!-- Skills -->
                @if(count($skills) > 0)
                <div class="section">
                    <div class="sidebar-section-title">{{ __('cv.skills') }}</div>
                    @php $groupedSkills = collect($skills)->groupBy('category'); @endphp
                    @if($groupedSkills->count() > 1)
                        @foreach($groupedSkills as $category => $categorySkills)
                            <div class="skill-group">
                                <div class="skill-category">{{ $category ?: 'Other' }}</div>
                                @foreach($categorySkills as $skill)
                                    <span class="skill-tag">{{ $skill['name'] }}</span>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="skill-group">
                            @foreach($skills as $skill)
                                <span class="skill-tag">{{ $skill['name'] }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif

                <!-- Education -->
                @if(count($educations) > 0 && (isset($educations[0]['school']) && $educations[0]['school']))
                <div class="section">
                    <div class="sidebar-section-title">{{ __('cv.education') }}</div>
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

                <!-- Certifications -->
                @if(count($certifications) > 0 && (isset($certifications[0]['name']) && $certifications[0]['name']))
                <div class="section">
                    <div class="sidebar-section-title">{{ __('cv.certifications') }}</div>
                    @foreach($certifications as $cert)
                    @if(isset($cert['name']) && $cert['name'])
                    <div class="cert-item">
                        {{ $cert['name'] }}
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif

                <!-- Languages -->
                @if(count($languages) > 0)
                <div class="section">
                    <div class="sidebar-section-title">{{ __('cv.languages') }}</div>
                    @foreach($languages as $lang)
                        <div class="lang-tag"><span class="lang-name">{{ $lang['name'] }}</span> &ndash; {{ $lang['level'] }}</div>
                    @endforeach
                </div>
                @endif

            </div>

            <!-- ===== MAIN COLUMN ===== -->
            <div class="main-col">
                
                <!-- Experience -->
                @if(count($experiences) > 0)
                <div class="section">
                    <div class="section-title">{{ __('cv.work_experience') }}</div>
                    @foreach($experiences as $exp)
                    <div class="exp-item">
                        <table width="100%">
                            <tr>
                                <td><div class="exp-role">{{ $exp['role'] }}</div></td>
                                <td class="exp-date" style="text-align: right; width: 30%;">{{ $exp['date_range'] }}</td>
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
                    <div class="project-item">
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
