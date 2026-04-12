<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomelabService extends Model
{
    protected $fillable = [
        'vmid',
        'name',
        'alias',
        'node_label',
        'icon',
        'type',
        'is_visible',
        'sort_order',
        'cached_status',
        'cached_cpu',
        'cached_memory',
        'cached_uptime',
        'last_status_check',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'last_status_check' => 'datetime',
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true)->orderBy('sort_order');
    }

    /**
     * Persist last known status from Proxmox API to database.
     * Used as fallback when Proxmox API is unreachable.
     */
    public function updateCachedStatus(array $data): void
    {
        $this->update([
            'cached_status' => $data['status'] ?? 'unknown',
            'cached_cpu' => $data['cpu'] ?? null,
            'cached_memory' => $data['memory'] ?? null,
            'cached_uptime' => $data['uptime'] ?? null,
            'last_status_check' => now(),
        ]);
    }

    /**
     * Get the cached status as a formatted array (same shape as live status).
     */
    public function getCachedStatusArray(): array
    {
        return [
            'name' => $this->alias ?? $this->name,
            'node' => $this->node_label,
            'status' => $this->cached_status,
            'cpu' => $this->cached_cpu ?? 'N/A',
            'memory' => $this->cached_memory ?? 'N/A',
            'uptime' => $this->cached_uptime ?? 'N/A',
            'icon' => $this->icon,
            'color' => in_array($this->cached_status, ['online', 'running']) ? 'yellow' : 'red',
        ];
    }
}
