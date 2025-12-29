<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Polling;

class ServerStatus extends Component
{
    public array $servers = [];

    public function mount()
    {
        $this->refreshStatus();
    }

    #[Polling('10s')]
    public function refreshStatus()
    {
        $this->servers = [
            [
                'name' => 'Proxmox VE',
                'node' => 'pve-01',
                'status' => 'online',
                'metric_label' => 'CPU Load',
                'metric_value' => rand(5, 15) . '%',
                'icon' => 'server',
                'color' => 'green',
            ],
            [
                'name' => 'Wazuh SIEM',
                'node' => 'wazuh-manager',
                'status' => 'active',
                'metric_label' => 'Events/sec',
                'metric_value' => rand(120, 450),
                'icon' => 'shield-check',
                'color' => 'cyan',
            ],
            [
                'name' => 'Tailscale VPN',
                'node' => 'tailscale0',
                'status' => 'encrypted',
                'metric_label' => 'Internal IP',
                'metric_value' => '100.' . rand(64, 127) . '.x.x',
                'icon' => 'lock',
                'color' => 'blue',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.server-status');
    }
}
