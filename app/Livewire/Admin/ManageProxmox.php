<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\ProxmoxService;
use App\Models\Project;
use App\Models\HomelabService;
use Illuminate\Support\Str;

class ManageProxmox extends Component
{
    public array $resources = [];
    public array $linkedVmids = [];
    public array $homelabVmids = [];
    public array $homelabAliases = [];
    public bool $loading = false;
    public ?int $editingVmid = null;
    public string $newAlias = '';

    public function mount()
    {
        $this->loadResources();
    }

    public function loadResources()
    {
        $this->loading = true;

        $service = app(ProxmoxService::class);

        if (!$service->isConfigured()) {
            $this->resources = [];
            $this->loading = false;
            return;
        }

        $this->resources = $service->listAll();

        // Load which vmids are linked to projects (Landing Page)
        $this->linkedVmids = Project::whereNotNull('proxmox_vmid')
            ->pluck('show_on_landing', 'proxmox_vmid')
            ->toArray();

        // Load which vmids are in homelab_services (Home Lab section)
        $this->homelabVmids = HomelabService::pluck('is_visible', 'vmid')->toArray();
        $this->homelabAliases = HomelabService::pluck('alias', 'vmid')->toArray();

        $this->loading = false;
    }

    public function refreshList()
    {
        \Illuminate\Support\Facades\Cache::forget('proxmox_vms_list');
        \Illuminate\Support\Facades\Cache::forget('proxmox_lxc_list');
        $this->loadResources();
        session()->flash('message', 'Proxmox data refreshed!');
    }

    public function toggleLanding(int $vmid, string $name, string $typeLabel)
    {
        $project = Project::where('proxmox_vmid', $vmid)->first();

        if ($project) {
            $project->update(['show_on_landing' => !$project->show_on_landing]);
            $this->linkedVmids[$vmid] = $project->show_on_landing;
        } else {
            $resource = collect($this->resources)->firstWhere('vmid', $vmid);
            $project = Project::create([
                'title' => $name,
                'slug' => Str::slug($name),
                'description' => "Proxmox {$typeLabel} resource (VMID: {$vmid})",
                'status' => ($resource && $resource['is_running']) ? 'online' : 'offline',
                'type' => 'Home Lab',
                'tech_stack' => ['Proxmox VE'],
                'show_on_landing' => true,
                'proxmox_vmid' => $vmid,
            ]);
            $project->seo()->create([
                'title' => $name,
                'description' => "Proxmox {$typeLabel} resource",
                'keywords' => 'proxmox, homelab',
            ]);
            $this->linkedVmids[$vmid] = true;
        }
    }

    public function toggleHomelab(int $vmid, string $name, string $typeLabel)
    {
        $service = HomelabService::where('vmid', $vmid)->first();

        if ($service) {
            $service->update(['is_visible' => !$service->is_visible]);
            $this->homelabVmids[$vmid] = $service->is_visible;

            // Clear cache for this service
            \Illuminate\Support\Facades\Cache::forget("homelab_{$vmid}_status");
        } else {
            // Determine icon based on type
            $icons = [
                'qemu' => 'monitor',
                'lxc' => 'container',
                'node' => 'server',
            ];

            $type = match($typeLabel) {
                'VM' => 'qemu',
                'LXC' => 'lxc',
                'Node' => 'node',
                default => 'qemu'
            };

            $maxOrder = HomelabService::max('sort_order') ?? 0;

            HomelabService::create([
                'vmid' => $vmid,
                'name' => $name,
                'node_label' => 'Worker',
                'icon' => $icons[$type] ?? 'server',
                'type' => $type,
                'is_visible' => true,
                'sort_order' => $maxOrder + 1,
            ]);
            $this->homelabVmids[$vmid] = true;
        }
    }

    public function startEditAlias(int $vmid)
    {
        $this->editingVmid = $vmid;
        $this->newAlias = $this->homelabAliases[$vmid] ?? '';
    }

    public function saveAlias()
    {
        $service = HomelabService::where('vmid', $this->editingVmid)->first();
        if ($service) {
            $service->update(['alias' => $this->newAlias]);
            $this->homelabAliases[$this->editingVmid] = $this->newAlias;
            
            // Clear cache
            \Illuminate\Support\Facades\Cache::forget("homelab_{$this->editingVmid}_status");
        }
        
        $this->cancelEdit();
        session()->flash('message', 'Alias updated successfully!');
    }

    public function cancelEdit()
    {
        $this->editingVmid = null;
        $this->newAlias = '';
    }

    public function isLinked(int $vmid): bool
    {
        return isset($this->linkedVmids[$vmid]) && $this->linkedVmids[$vmid];
    }

    public function isOnHomelab(int $vmid): bool
    {
        return isset($this->homelabVmids[$vmid]) && $this->homelabVmids[$vmid];
    }

    public function render()
    {
        return view('livewire.admin.manage-proxmox')
            ->layout('layouts.admin', ['title' => 'Proxmox Resources']);
    }
}
