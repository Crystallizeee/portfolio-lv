<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\ProxmoxService;
use App\Models\Project;
use Illuminate\Support\Str;

class ManageProxmox extends Component
{
    public array $resources = [];
    public array $linkedVmids = [];
    public bool $loading = false;

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

        // Load which vmids are already linked to projects
        $this->linkedVmids = Project::whereNotNull('proxmox_vmid')
            ->pluck('show_on_landing', 'proxmox_vmid')
            ->toArray();

        $this->loading = false;
    }

    public function refreshList()
    {
        // Clear Proxmox cache to force fresh fetch
        \Illuminate\Support\Facades\Cache::forget('proxmox_vms_list');
        \Illuminate\Support\Facades\Cache::forget('proxmox_lxc_list');
        $this->loadResources();
        session()->flash('message', 'Proxmox data refreshed!');
    }

    public function toggleLanding(int $vmid, string $name, string $typeLabel)
    {
        $project = Project::where('proxmox_vmid', $vmid)->first();

        if ($project) {
            // Toggle existing project visibility
            $project->update(['show_on_landing' => !$project->show_on_landing]);
            $this->linkedVmids[$vmid] = $project->show_on_landing;
        } else {
            // Create a new project linked to this Proxmox resource
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

        session()->flash('message', "Landing page visibility updated for {$name}!");
    }

    public function unlinkProject(int $vmid)
    {
        $project = Project::where('proxmox_vmid', $vmid)->first();
        if ($project) {
            $project->update(['show_on_landing' => false]);
            $this->linkedVmids[$vmid] = false;
            session()->flash('message', "'{$project->title}' hidden from landing page.");
        }
    }

    public function isLinked(int $vmid): bool
    {
        return isset($this->linkedVmids[$vmid]) && $this->linkedVmids[$vmid];
    }

    public function hasProject(int $vmid): bool
    {
        return array_key_exists($vmid, $this->linkedVmids);
    }

    public function render()
    {
        return view('livewire.admin.manage-proxmox')
            ->layout('layouts.admin', ['title' => 'Proxmox Resources']);
    }
}
