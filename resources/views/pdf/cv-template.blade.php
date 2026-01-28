<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $personal['name'] }} - CV</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .name {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .contact-info {
            font-size: 12px;
            color: #555;
        }
        .contact-separator {
            margin: 0 5px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
            color: #222;
        }
        .item {
            margin-bottom: 15px;
        }
        .item-header {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }
        .left-col {
            display: table-cell;
            font-weight: bold;
            font-size: 14px;
        }
        .right-col {
            display: table-cell;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #555;
        }
        .subtitle {
            font-style: italic;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .description {
            font-size: 13px;
            text-align: justify;
        }
        .skills-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .skill-item {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 2px 8px;
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 12px;
            border-radius: 3px;
        }
        ul {
            margin-top: 5px;
            padding-left: 20px;
        }
        li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="name">{{ $personal['name'] }}</div>
        <div class="contact-info">
            {{ $personal['email'] }}
            @if($personal['phone']) <span class="contact-separator">|</span> {{ $personal['phone'] }} @endif
            @if($personal['address']) <span class="contact-separator">|</span> {{ $personal['address'] }} @endif
            <br>
            @if($personal['linkedin']) {{ $personal['linkedin'] }} @endif
            @if($personal['website']) <span class="contact-separator">|</span> {{ $personal['website'] }} @endif
        </div>
    </div>

    @if($personal['summary'])
    <div class="section">
        <div class="section-title">Professional Summary</div>
        <div class="description">
            {{ $personal['summary'] }}
        </div>
    </div>
    @endif

    @if(count($experiences) > 0)
    <div class="section">
        <div class="section-title">Work Experience</div>
        @foreach($experiences as $exp)
        <div class="item">
            <div class="item-header">
                <div class="left-col">{{ $exp['company'] }}</div>
                <div class="right-col">{{ $exp['date_range'] }}</div>
            </div>
            <div class="subtitle">{{ $exp['role'] }}</div>
            <div class="description">
                {!! nl2br(e($exp['description'])) !!}
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(count($educations) > 0 && $educations[0]['school'])
    <div class="section">
        <div class="section-title">Education</div>
        @foreach($educations as $edu)
        @if($edu['school'])
        <div class="item">
            <div class="item-header">
                <div class="left-col">{{ $edu['school'] }}</div>
                <div class="right-col">{{ $edu['year'] }}</div>
            </div>
            <div class="subtitle">{{ $edu['degree'] }}</div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    @if(count($skills) > 0)
    <div class="section">
        <div class="section-title">Skills</div>
        <div class="skills-list">
            @foreach($skills as $skill)
            <span class="skill-item">{{ $skill['name'] }}</span>
            @endforeach
        </div>
    </div>
    @endif

    @if(count($certifications) > 0 && $certifications[0]['name'])
    <div class="section">
        <div class="section-title">Certifications</div>
        @foreach($certifications as $cert)
        @if($cert['name'])
        <div class="item">
            <div class="item-header">
                <div class="left-col">{{ $cert['name'] }}</div>
                <div class="right-col">{{ $cert['year'] }}</div>
            </div>
            <div class="subtitle">{{ $cert['issuer'] }}</div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

</body>
</html>
