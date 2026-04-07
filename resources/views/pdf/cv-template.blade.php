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
            border-bottom: 3px solid #0f172a;
        }

        .name {
            font-size: 26px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .professional-title {
            font-size: 11px;
            color: #2563eb;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .contact-table td {
            vertical-align: top;
            padding: 2px 0;
        }

        .contact-item {
            font-size: 9.5px;
            color: #475569;
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
            color: #0f172a;
            margin-bottom: 12px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
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
            position: relative;
        }

        .exp-role {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }

        .exp-company {
            font-size: 10px;
            color: #2563eb;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .exp-date {
            font-size: 9px;
            font-weight: 600;
            color: #64748b;
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
            border-left: 3px solid #2563eb;
        }

        .project-name {
            font-size: 10.5px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .project-desc {
            font-size: 9px;
            color: #475569;
            line-height: 1.5;
        }

        /* ===== SUMMARY ===== */
        .summary-box {
            background-color: #f1f5f9;
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
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            border-left: 3px solid #cbd5e1;
            padding-left: 8px;
        }

        .skill-group { margin-bottom: 15px; }
        .skill-category { font-size: 8.5px; font-weight: bold; color: #475569; margin-bottom: 5px; }

        .skill-tag {
            display: inline-block;
            background-color: #ffffff;
            color: #0f172a;
            padding: 2px 8px;
            font-size: 8.5px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-right: 4px;
            margin-bottom: 5px;
        }

        .edu-item { margin-bottom: 10px; }
        .edu-degree { font-size: 10px; font-weight: bold; color: #0f172a; }
        .edu-school { font-size: 9px; color: #2563eb; font-weight: 500; }
        .edu-year { font-size: 8.5px; color: #64748b; }

        .cert-item { margin-bottom: 6px; font-size: 9px; color: #334155; }
        .cert-bullet { color: #2563eb; margin-right: 5px; }

        .lang-tag {
            display: block;
            font-size: 9px;
            color: #475569;
            margin-bottom: 4px;
        }
        .lang-name { font-weight: bold; color: #0f172a; }

    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <div class="header">
        <table width="100%">
            <tr>
                <td width="60%" style="vertical-align: top;">
                    <div class="name">{{ $personal['name'] }}</div>
                    <div class="professional-title">{{ $personal['professional_title'] }}</div>
                    <div style="font-size: 9px; color: #64748b; margin-top: 5px;">{{ $personal['address'] }}</div>
                </td>
                <td width="40%" style="vertical-align: top; text-align: right;">
                    @if($personal['phone'])
                        <div class="contact-item">
                            {{ $personal['phone'] }}
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l2.18-2.18a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                    @endif
                    <div class="contact-item">
                        {{ $personal['email'] }}
                        <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    @if($personal['linkedin'])
                        <div class="contact-item">
                            {{ str_replace(['https://', 'http://'], '', $personal['linkedin']) }}
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </div>
                    @endif
                    @if($personal['website'])
                        <div class="contact-item">
                            {{ str_replace(['https://', 'http://'], '', $personal['website']) }}
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        </div>
                    @endif
                    @if($personal['github'])
                        <div class="contact-item">
                            {{ str_replace(['https://', 'http://'], '', $personal['github']) }}
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
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
                        <span class="cert-bullet">&#9679;</span>
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
