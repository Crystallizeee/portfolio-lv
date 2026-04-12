<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Polling;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\HomelabService;

class ServerStatus extends Component
{
    public array $servers = [];

    public function mount()
    {
        $this->refreshStatus();
    }

    #[Polling('30s')]
    public function refreshStatus()
    {
        $services = HomelabService::visible()->get();

        if ($services->isEmpty()) {
            $this->servers = [];
            return;
        }

        $this->servers = $services->map(function ($service) {
            if ($service->type === 'node') {
                return $this->getNodeStatus($service);
            } elseif ($service->type === 'qemu') {
                return $this->getVmStatus($service);
            } else {
                return $this->getLxcStatus($service);
            }
        })->toArray();
    }

    protected function getNodeStatus(HomelabService $service): array
    {
        return Cache::remember("homelab_{$service->vmid}_status", 30, function () use ($service) {
            try {
                $host = config('services.proxmox.host');
                $node = config('services.proxmox.node');
                $tokenId = config('services.proxmox.token_id');
                $tokenSecret = config('services.proxmox.token_secret');

                if (!$host || !$tokenId || !$tokenSecret) return $this->fallbackStatus($service);

                $response = Http::withoutVerifying()
                    ->withHeaders(['Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"])
                    ->timeout(5)
                    ->get("https://{$host}:8006/api2/json/nodes/{$node}/status");

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    $result = [
                        'name' => $service->alias ?? $service->name,
                        'node' => $service->node_label,
                        'status' => 'online',
                        'cpu' => round($data['cpu'] * 100, 1) . '%',
                        'memory' => round(($data['memory']['used'] / ($data['memory']['total'] ?: 1)) * 100, 1) . '%',
                        'uptime' => $this->formatUptime($data['uptime'] ?? 0),
                        'icon' => $service->icon,
                        'color' => 'green',
                    ];

                    // Persist to DB for fallback
                    $service->updateCachedStatus($result);

                    return $result;
                }
                return $this->fallbackStatus($service);
            } catch (\Exception $e) {
                return $this->fallbackStatus($service);
            }
        });
    }

    protected function getVmStatus(HomelabService $service): array
    {
        return Cache::remember("homelab_{$service->vmid}_status", 30, function () use ($service) {
            try {
                $host = config('services.proxmox.host');
                $node = config('services.proxmox.node');
                $tokenId = config('services.proxmox.token_id');
                $tokenSecret = config('services.proxmox.token_secret');

                if (!$host || !$tokenId || !$tokenSecret) return $this->fallbackStatus($service);

                $response = Http::withoutVerifying()
                    ->withHeaders(['Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"])
                    ->timeout(5)
                    ->get("https://{$host}:8006/api2/json/nodes/{$node}/qemu/{$service->vmid}/status/current");

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    $status = $data['status'] ?? 'unknown';
                    $isRunning = $status === 'running';

                    $result = [
                        'name' => $service->alias ?? $service->name,
                        'node' => $service->node_label,
                        'status' => $isRunning ? 'running' : $status,
                        'cpu' => round(($data['cpu'] ?? 0) * 100, 1) . '%',
                        'memory' => round((($data['mem'] ?? 0) / ($data['maxmem'] ?: 1)) * 100, 1) . '%',
                        'uptime' => $this->formatUptime($data['uptime'] ?? 0),
                        'icon' => $service->icon,
                        'color' => $isRunning ? 'green' : 'red',
                    ];

                    // Persist to DB for fallback
                    $service->updateCachedStatus($result);

                    return $result;
                }
                return $this->fallbackStatus($service);
            } catch (\Exception $e) {
                return $this->fallbackStatus($service);
            }
        });
    }

    protected function getLxcStatus(HomelabService $service): array
    {
        return Cache::remember("homelab_{$service->vmid}_status", 30, function () use ($service) {
            try {
                $host = config('services.proxmox.host');
                $node = config('services.proxmox.node');
                $tokenId = config('services.proxmox.token_id');
                $tokenSecret = config('services.proxmox.token_secret');

                if (!$host || !$tokenId || !$tokenSecret) return $this->fallbackStatus($service);

                $response = Http::withoutVerifying()
                    ->withHeaders(['Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"])
                    ->timeout(5)
                    ->get("https://{$host}:8006/api2/json/nodes/{$node}/lxc/{$service->vmid}/status/current");

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    $status = $data['status'] ?? 'unknown';
                    $isRunning = $status === 'running';

                    $result = [
                        'name' => $service->alias ?? $service->name,
                        'node' => $service->node_label,
                        'status' => $isRunning ? 'running' : $status,
                        'cpu' => round(($data['cpu'] ?? 0) * 100, 1) . '%',
                        'memory' => round((($data['mem'] ?? 0) / ($data['maxmem'] ?: 1)) * 100, 1) . '%',
                        'uptime' => $this->formatUptime($data['uptime'] ?? 0),
                        'icon' => $service->icon,
                        'color' => $isRunning ? 'green' : 'red',
                    ];

                    // Persist to DB for fallback
                    $service->updateCachedStatus($result);

                    return $result;
                }
                return $this->fallbackStatus($service);
            } catch (\Exception $e) {
                return $this->fallbackStatus($service);
            }
        });
    }

    /**
     * Graceful degradation: DB cached status → default offline.
     * If Proxmox API is down, return last known DB status (yellow indicator).
     * If no DB cache exists, return hardcoded offline (red indicator).
     */
    protected function fallbackStatus(HomelabService $service): array
    {
        // Try DB cached status first
        if ($service->last_status_check !== null) {
            return $service->getCachedStatusArray();
        }

        // Ultimate fallback: no data at all
        return $this->defaultStatus($service);
    }

    protected function defaultStatus(HomelabService $service): array
    {
        return [
            'name' => $service->alias ?? $service->name,
            'node' => $service->node_label,
            'status' => 'offline',
            'cpu' => 'N/A',
            'memory' => 'N/A',
            'uptime' => 'N/A',
            'icon' => $service->icon,
            'color' => 'red',
        ];
    }

    protected function formatUptime(int $seconds): string
    {
        if ($seconds <= 0) return 'N/A';

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);

        if ($days > 0) return "{$days}d {$hours}h";

        $minutes = floor(($seconds % 3600) / 60);
        return "{$hours}h {$minutes}m";
    }

    public function render()
    {
        return view('livewire.server-status');
    }
}
