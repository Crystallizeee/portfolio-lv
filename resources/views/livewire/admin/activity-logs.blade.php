<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> cat /var/log/syslog | grep "activity"
            </div>
            <p class="text-slate-400">Track all changes and system events</p>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-700/50 text-slate-400 text-sm">
                        <th class="p-4 font-mono font-normal">Timestamp</th>
                        <th class="p-4 font-mono font-normal">Action</th>
                        <th class="p-4 font-mono font-normal">Description</th>
                        <th class="p-4 font-mono font-normal">Module</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="p-4 text-sm text-slate-500 font-mono">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    @if($log->action === 'create') bg-green-500/10 text-green-400
                                    @elseif($log->action === 'update') bg-blue-500/10 text-blue-400
                                    @elseif($log->action === 'delete') bg-red-500/10 text-red-400
                                    @else bg-slate-500/10 text-slate-400 @endif">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-white">
                                {{ $log->description }}
                            </td>
                            <td class="p-4 text-sm text-slate-400 font-mono text-xs">
                                {{ class_basename($log->model_type) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-700/50">
            {{ $logs->links() }}
        </div>
    </div>
</div>
