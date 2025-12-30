<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Polling;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
        $this->servers = [
            $this->getProxmoxStatus(),
            $this->getNextcloudStatus(),
            $this->getPortfolioStatus(),
            $this->getFinanceBotStatus(),
            [
                'name' => 'Tailscale VPN',
                'node' => 'mesh-network',
                'status' => 'connected',
                'cpu' => '-',
                'memory' => '-',
                'uptime' => 'Always On',
                'icon' => 'lock',
                'color' => 'green',
            ],
        ];
    }

    protected function getProxmoxStatus(): array
    {
        $default = [
            'name' => 'Proxmox VE',
            'node' => config('services.proxmox.node', 'pve-01'),
            'status' => 'offline',
            'cpu' => 'N/A',
            'memory' => 'N/A',
            'uptime' => 'N/A',
            'icon' => 'server',
            'color' => 'red',
        ];

        return Cache::remember('proxmox_status', 30, function () use ($default) {
            try {
                $host = config('services.proxmox.host');
                $node = config('services.proxmox.node');
                $tokenId = config('services.proxmox.token_id');
                $tokenSecret = config('services.proxmox.token_secret');

                if (!$host || !$tokenId || !$tokenSecret) {
                    return $default;
                }

                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"
                    ])
                    ->timeout(5)
                    ->get("https://{$host}:8006/api2/json/nodes/{$node}/status");

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    
                    $cpuUsage = round($data['cpu'] * 100, 1);
                    $memUsed = $data['memory']['used'] ?? 0;
                    $memTotal = $data['memory']['total'] ?? 1;
                    $memPercent = round(($memUsed / $memTotal) * 100, 1);
                    $uptime = $this->formatUptime($data['uptime'] ?? 0);
                    
                    return [
                        'name' => 'Proxmox VE',
                        'node' => $node,
                        'status' => 'online',
                        'cpu' => $cpuUsage . '%',
                        'memory' => $memPercent . '%',
                        'uptime' => $uptime,
                        'icon' => 'server',
                        'color' => 'green',
                    ];
                }

                return $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    protected function getNextcloudStatus(): array
    {
        return $this->getVmStatus(106, 'Nextcloud', 'VM-106', 'cloud');
    }

    protected function getPortfolioStatus(): array
    {
        return $this->getLxcStatus(104, 'Portfolio', 'LXC-104', 'globe');
    }

    protected function getFinanceBotStatus(): array
    {
        return $this->getLxcStatus(102, 'Finance Bot', 'LXC-102', 'bot');
    }

    protected function getVmStatus(int $vmid, string $name, string $nodeLabel, string $icon): array
    {
        $default = [
            'name' => $name,
            'node' => $nodeLabel,
            'status' => 'offline',
            'cpu' => 'N/A',
            'memory' => 'N/A',
            'uptime' => 'N/A',
            'icon' => $icon,
            'color' => 'red',
        ];

        return Cache::remember("{$name}_status", 30, function () use ($vmid, $name, $nodeLabel, $icon, $default) {
            try {
                $host = config('services.proxmox.host');
                $node = config('services.proxmox.node');
                $tokenId = config('services.proxmox.token_id');
                $tokenSecret = config('services.proxmox.token_secret');

                if (!$host || !$tokenId || !$tokenSecret) {
                    return $default;
                }

                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"
                    ])
                    ->timeout(5)
                    ->get("https://{$host}:8006/api2/json/nodes/{$node}/qemu/{$vmid}/status/current");

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    $status = $data['status'] ?? 'unknown';
                    $isRunning = $status === 'running';
                    
                    $cpuUsage = round(($data['cpu'] ?? 0) * 100, 1);
                    $memUsed = $data['mem'] ?? 0;
                    $memMax = $data['maxmem'] ?? 1;
                    $memPercent = round(($memUsed / $memMax) * 100, 1);
                    $uptime = $this->formatUptime($data['uptime'] ?? 0);
                    
                    return [
                        'name' => $name,
                        'node' => $nodeLabel,
                        'status' => $isRunning ? 'running' : $status,
                        'cpu' => $cpuUsage . '%',
                        'memory' => $memPercent . '%',
                        'uptime' => $uptime,
                        'icon' => $icon,
                        'color' => $isRunning ? 'green' : 'red',
                    ];
                }

                return $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    protected function getLxcStatus(int $ctid, string $name, string $nodeLabel, string $icon): array
    {
        $default = [
            'name' => $name,
            'node' => $nodeLabel,
            'status' => 'offline',
            'cpu' => 'N/A',
            'memory' => 'N/A',
            'uptime' => 'N/A',
            'icon' => $icon,
            'color' => 'red',
        ];

        return Cache::remember("{$name}_status", 30, function () use ($ctid, $name, $nodeLabel, $icon, $default) {
            try {
                $host = config('services.proxmox.host');
                $node = config('services.proxmox.node');
                $tokenId = config('services.proxmox.token_id');
                $tokenSecret = config('services.proxmox.token_secret');

                if (!$host || !$tokenId || !$tokenSecret) {
                    return $default;
                }

                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"
                    ])
                    ->timeout(5)
                    ->get("https://{$host}:8006/api2/json/nodes/{$node}/lxc/{$ctid}/status/current");

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    $status = $data['status'] ?? 'unknown';
                    $isRunning = $status === 'running';
                    
                    $cpuUsage = round(($data['cpu'] ?? 0) * 100, 1);
                    $memUsed = $data['mem'] ?? 0;
                    $memMax = $data['maxmem'] ?? 1;
                    $memPercent = round(($memUsed / $memMax) * 100, 1);
                    $uptime = $this->formatUptime($data['uptime'] ?? 0);
                    
                    return [
                        'name' => $name,
                        'node' => $nodeLabel,
                        'status' => $isRunning ? 'running' : $status,
                        'cpu' => $cpuUsage . '%',
                        'memory' => $memPercent . '%',
                        'uptime' => $uptime,
                        'icon' => $icon,
                        'color' => $isRunning ? 'green' : 'red',
                    ];
                }

                return $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    protected function formatUptime(int $seconds): string
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        
        if ($days > 0) {
            return "{$days}d {$hours}h";
        }
        
        $minutes = floor(($seconds % 3600) / 60);
        return "{$hours}h {$minutes}m";
    }

    public function render()
    {
        return view('livewire.server-status');
    }
}
