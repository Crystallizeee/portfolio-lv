<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 | System Malfunction</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'JetBrains Mono', monospace; }
        .scanline {
            width: 100%; height: 100px; z-index: 10; background: linear-gradient(0deg, rgba(0,0,0,0) 0%, rgba(255, 0, 0, 0.2) 50%, rgba(0,0,0,0) 100%); opacity: 0.1;
            position: absolute; bottom: 100%; animation: scanline 10s linear infinite; pointer-events: none;
        }
        @keyframes scanline { 0% { bottom: 100%; } 100% { bottom: -100%; } }
    </style>
</head>
<body class="bg-black text-slate-200 min-h-screen flex items-center justify-center p-4 overflow-hidden relative">
    <div class="scanline"></div>
    <div class="max-w-2xl w-full relative z-20">
        <div class="border-2 border-red-600 p-8 md:p-12 relative bg-slate-900/90 shadow-[0_0_50px_rgba(220,38,38,0.3)]">
            <!-- Content -->
            <div class="relative z-10 text-center">
                <div class="mb-6 inline-flex p-4 rounded-full bg-red-600/10 border border-red-600 animate-pulse">
                    <i data-lucide="server-crash" class="w-12 h-12 text-red-600"></i>
                </div>
                
                <h1 class="text-6xl md:text-8xl font-bold text-white mb-2">500</h1>
                <h2 class="text-xl md:text-2xl font-bold text-red-500 mb-6 tracking-widest uppercase">System Malfunction</h2>
                
                <div class="font-mono text-sm md:text-base text-red-400 mb-8 space-y-2 bg-black/50 p-6 border-l-4 border-red-600 text-left overflow-hidden">
                    <div>> CRITICAL_PROCESS_DIED</div>
                    <div>> INITIATING_CORE_DUMP... <span class="animate-pulse">DONE</span></div>
                    <div>> SYSTEM_INTEGRITY: <span class="text-red-600 font-bold">COMPROMISED</span></div>
                    <div>> Please contact administrator or try again later.</div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="px-6 py-3 border border-red-600 text-red-500 hover:bg-red-600 hover:text-white rounded-none font-medium transition-all flex items-center gap-2">
                        <i data-lucide="power" class="w-4 h-4"></i>
                        Reboot System (Home)
                    </a>
                </div>
            </div>
            
            <!-- Decor -->
            <div class="absolute top-0 left-0 w-4 h-4 border-t-4 border-l-4 border-red-600"></div>
            <div class="absolute top-0 right-0 w-4 h-4 border-t-4 border-r-4 border-red-600"></div>
            <div class="absolute bottom-0 left-0 w-4 h-4 border-b-4 border-l-4 border-red-600"></div>
            <div class="absolute bottom-0 right-0 w-4 h-4 border-b-4 border-r-4 border-red-600"></div>
        </div>
    </div>
    <script> lucide.createIcons(); </script>
</body>
</html>
