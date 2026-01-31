<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | Target Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'JetBrains Mono', monospace; }
        .glitch { position: relative; }
        .glitch::before, .glitch::after {
            content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        }
        .glitch::before { left: 2px; text-shadow: -1px 0 red; clip: rect(24px, 550px, 90px, 0); animation: glitch-anim-2 3s infinite linear alternate-reverse; }
        .glitch::after { left: -2px; text-shadow: -1px 0 blue; clip: rect(85px, 550px, 140px, 0); animation: glitch-anim 2.5s infinite linear alternate-reverse; }
        @keyframes glitch-anim { 0% { clip: rect(10px, 9999px, 30px, 0); } 20% { clip: rect(80px, 9999px, 100px, 0); } 40% { clip: rect(10px, 9999px, 80px, 0); } 60% { clip: rect(50px, 9999px, 90px, 0); } 80% { clip: rect(20px, 9999px, 60px, 0); } 100% { clip: rect(70px, 9999px, 30px, 0); } }
        @keyframes glitch-anim-2 { 0% { clip: rect(60px, 9999px, 90px, 0); } 20% { clip: rect(10px, 9999px, 50px, 0); } 40% { clip: rect(90px, 9999px, 10px, 0); } 60% { clip: rect(20px, 9999px, 60px, 0); } 80% { clip: rect(50px, 9999px, 20px, 0); } 100% { clip: rect(10px, 9999px, 80px, 0); } }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="glass-card border border-red-500/30 p-8 md:p-12 relative overflow-hidden group">
            <!-- Background Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/10 rounded-full blur-3xl -z-10"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl -z-10"></div>
            
            <!-- Content -->
            <div class="relative z-10 text-center">
                <div class="mb-6 inline-flex p-4 rounded-full bg-red-500/10 border border-red-500/30">
                    <i data-lucide="shield-alert" class="w-12 h-12 text-red-500"></i>
                </div>
                
                <h1 class="text-6xl md:text-8xl font-bold text-white mb-2 glitch" data-text="404">404</h1>
                <h2 class="text-xl md:text-2xl font-bold text-red-400 mb-6 tracking-widest uppercase">Target Not Found</h2>
                
                <div class="font-mono text-sm md:text-base text-slate-400 mb-8 space-y-1 bg-black/30 p-4 rounded-lg border border-slate-700/50 text-left">
                    <div><span class="text-green-500">root@portfolio:~$</span> ping {{ request()->path() }}</div>
                    <div>PING {{ request()->path() }} (0.0.0.0): 56 data bytes</div>
                    <div class="text-red-400">Request timeout for icmp_seq 0</div>
                    <div class="text-red-400">Request timeout for icmp_seq 1</div>
                    <div class="text-red-400">Request timeout for icmp_seq 2</div>
                    <div>--- {{ request()->path() }} ping statistics ---</div>
                    <div>3 packets transmitted, 0 packets received, 100.0% packet loss</div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-medium transition-all flex items-center gap-2 group">
                        <i data-lucide="terminal" class="w-4 h-4 group-hover:animate-pulse"></i>
                        Return to Base
                    </a>
                </div>
            </div>
            
            <!-- Decor -->
            <div class="absolute top-4 left-4 flex gap-2">
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
            </div>
        </div>
        
        <div class="text-center mt-8 text-xs text-slate-600 font-mono">
            System ID: {{ Str::uuid() }} <br>
            Timestamp: {{ now()->toIso8601String() }}
        </div>
    </div>
    <script> lucide.createIcons(); </script>
</body>
</html>
