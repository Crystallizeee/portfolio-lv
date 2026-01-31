<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 | Access Denied</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="glass-card border border-yellow-500/30 p-8 md:p-12 relative overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl -z-10"></div>
            
            <!-- Content -->
            <div class="relative z-10 text-center">
                <div class="mb-6 inline-flex p-4 rounded-full bg-yellow-500/10 border border-yellow-500/30">
                    <i data-lucide="lock" class="w-12 h-12 text-yellow-500"></i>
                </div>
                
                <h1 class="text-6xl md:text-8xl font-bold text-white mb-2">403</h1>
                <h2 class="text-xl md:text-2xl font-bold text-yellow-500 mb-6 tracking-widest uppercase">Access Denied</h2>
                
                <div class="font-mono text-sm md:text-base text-slate-400 mb-8 p-4 rounded-lg border border-slate-700/50 bg-black/30">
                    <p class="mb-2">User: {{ auth()->user()->email ?? 'Guest' }}</p>
                    <p class="mb-2">Role: {{ auth()->user()->role ?? 'Unauthorized' }}</p>
                    <p class="text-red-400 font-bold">Error: Insufficient Privileges</p>
                    <p class="text-xs text-slate-500 mt-2">You do not have permission to access this resource.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-medium transition-all flex items-center gap-2 border border-slate-600">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script> lucide.createIcons(); </script>
</body>
</html>
