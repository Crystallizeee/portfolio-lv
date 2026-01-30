@component('layouts.admin')
    @slot('title') Backup & Restore @endslot

    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="terminal-text font-mono text-sm mb-1">
                    <span class="text-slate-500">$</span> mysqldump --all-databases > backup.sql
                </div>
                <p class="text-slate-400">Manage your data safety</p>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center space-x-3 text-green-400">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center space-x-3 text-red-400">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Export -->
            <div class="glass-card p-8">
                <div class="w-16 h-16 rounded-2xl bg-cyan-500/20 flex items-center justify-center mb-6">
                    <i data-lucide="download-cloud" class="w-8 h-8 text-cyan-400"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Export Data</h3>
                <p class="text-slate-400 mb-6 text-sm">Download a complete JSON backup of your portfolio data (Projects, Skills, Experience, Education, etc).</p>
                
                <a href="{{ route('admin.backup.export') }}" class="w-full flex items-center justify-center space-x-2 px-5 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl text-white font-bold hover:from-cyan-400 hover:to-blue-400 transition-all shadow-lg shadow-cyan-500/20">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    <span>Download Backup</span>
                </a>
            </div>

            <!-- Import -->
            <div class="glass-card p-8">
                <div class="w-16 h-16 rounded-2xl bg-purple-500/20 flex items-center justify-center mb-6">
                    <i data-lucide="upload-cloud" class="w-8 h-8 text-purple-400"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Restore Data</h3>
                <p class="text-slate-400 mb-6 text-sm">Restore your data from a JSON backup file. <span class="text-red-400">Warning: This will overwrite existing data.</span></p>
                
                <form action="{{ route('admin.backup.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Select Backup File</label>
                        <input type="file" name="backup_file" accept=".json" required class="block w-full text-sm text-slate-400
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-purple-500/10 file:text-purple-400
                            hover:file:bg-purple-500/20
                            cursor-pointer
                        "/>
                    </div>
                    <button type="submit" onclick="return confirm('Are you sure? This will OVERWRITE your current data.')" class="w-full flex items-center justify-center space-x-2 px-5 py-3 bg-slate-700 hover:bg-slate-600 rounded-xl text-white font-bold transition-all border border-slate-600">
                        <i data-lucide="upload" class="w-5 h-5"></i>
                        <span>Restore Backup</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endcomponent
