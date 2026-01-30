<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogs extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = ActivityLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.admin.activity-logs', [
            'logs' => $logs
        ])->layout('layouts.admin', ['title' => 'Activity Logs']);
    }
}
