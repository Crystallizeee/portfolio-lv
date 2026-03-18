<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ProxmoxService
{
    protected string $host;
    protected string $node;
    protected string $tokenId;
    protected string $tokenSecret;
    protected bool $configured = false;

    public function __construct()
    {
        $this->host = config('services.proxmox.host', '');
        $this->node = config('services.proxmox.node', 'pve-01');
        $this->tokenId = config('services.proxmox.token_id', '');
        $this->tokenSecret = config('services.proxmox.token_secret', '');
        $this->configured = $this->host && $this->tokenId && $this->tokenSecret;
    }

    public function isConfigured(): bool
    {
        return $this->configured;
    }

    /**
     * List all QEMU VMs on the node.
     */
    public function listVMs(): array
    {
        return Cache::remember('proxmox_vms_list', 30, function () {
            if (!$this->configured) return [];

            try {
                $response = $this->apiGet("/nodes/{$this->node}/qemu");
                if ($response->successful()) {
                    return collect($response->json()['data'] ?? [])
                        ->map(fn($vm) => $this->normalizeResource($vm, 'qemu'))
                        ->sortBy('vmid')
                        ->values()
                        ->toArray();
                }
            } catch (\Exception $e) {
                // Silently fail
            }

            return [];
        });
    }

    /**
     * List all LXC containers on the node.
     */
    public function listContainers(): array
    {
        return Cache::remember('proxmox_lxc_list', 30, function () {
            if (!$this->configured) return [];

            try {
                $response = $this->apiGet("/nodes/{$this->node}/lxc");
                if ($response->successful()) {
                    return collect($response->json()['data'] ?? [])
                        ->map(fn($ct) => $this->normalizeResource($ct, 'lxc'))
                        ->sortBy('vmid')
                        ->values()
                        ->toArray();
                }
            } catch (\Exception $e) {
                // Silently fail
            }

            return [];
        });
    }

    /**
     * Get all resources (VMs + Containers) combined.
     */
    public function listAll(): array
    {
        $vms = $this->listVMs();
        $containers = $this->listContainers();

        return collect(array_merge($vms, $containers))
            ->sortBy('vmid')
            ->values()
            ->toArray();
    }

    /**
     * Normalize a Proxmox resource into a consistent format.
     */
    protected function normalizeResource(array $data, string $type): array
    {
        $status = $data['status'] ?? 'unknown';
        $isRunning = $status === 'running';

        $cpuUsage = round(($data['cpu'] ?? 0) * 100, 1);
        $memUsed = $data['mem'] ?? 0;
        $memMax = $data['maxmem'] ?? 1;
        $memPercent = $memMax > 0 ? round(($memUsed / $memMax) * 100, 1) : 0;
        $uptime = $this->formatUptime($data['uptime'] ?? 0);

        return [
            'vmid' => $data['vmid'],
            'name' => $data['name'] ?? "Unknown-{$data['vmid']}",
            'type' => $type, // 'qemu' or 'lxc'
            'type_label' => $type === 'qemu' ? 'VM' : 'LXC',
            'status' => $status,
            'is_running' => $isRunning,
            'cpu' => $cpuUsage,
            'memory' => $memPercent,
            'uptime' => $uptime,
            'disk_used' => $data['disk'] ?? 0,
            'disk_max' => $data['maxdisk'] ?? 0,
        ];
    }

    protected function formatUptime(int $seconds): string
    {
        if ($seconds <= 0) return '-';

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);

        if ($days > 0) return "{$days}d {$hours}h";

        $minutes = floor(($seconds % 3600) / 60);
        return "{$hours}h {$minutes}m";
    }

    /**
     * Make an authenticated GET request to the Proxmox API.
     */
    protected function apiGet(string $endpoint): \Illuminate\Http\Client\Response
    {
        return Http::withoutVerifying()
            ->withHeaders([
                'Authorization' => "PVEAPIToken={$this->tokenId}={$this->tokenSecret}"
            ])
            ->timeout(5)
            ->get("https://{$this->host}:8006/api2/json{$endpoint}");
    }
}
