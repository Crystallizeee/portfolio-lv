<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminLogin;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\ManageProjects;
use App\Livewire\Admin\ManageExperiences;
use App\Livewire\Admin\ManageSkills;

Route::get('/', function () {
    return view('welcome');
});

// Debug Proxmox API
Route::get('/debug-proxmox', function () {
    $host = config('services.proxmox.host');
    $node = config('services.proxmox.node');
    $tokenId = config('services.proxmox.token_id');
    $tokenSecret = config('services.proxmox.token_secret');
    
    $result = [
        'config' => [
            'host' => $host,
            'node' => $node,
            'token_id' => $tokenId ? 'SET' : 'NOT SET',
            'token_secret' => $tokenSecret ? 'SET' : 'NOT SET',
        ]
    ];
    
    if ($host && $tokenId && $tokenSecret) {
        try {
            // Test node status
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"
                ])
                ->timeout(5)
                ->get("https://{$host}:8006/api2/json/nodes/{$node}/status");
            
            $result['node_api_status'] = $response->status();
            
            // Test Nextcloud VM 106 (QEMU)
            $containerResponse = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => "PVEAPIToken={$tokenId}={$tokenSecret}"
                ])
                ->timeout(5)
                ->get("https://{$host}:8006/api2/json/nodes/{$node}/qemu/106/status/current");
            
            $result['container_api_status'] = $containerResponse->status();
            $result['container_response'] = $containerResponse->json();
            
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }
    }
    
    return response()->json($result);
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', AdminLogin::class)->name('admin.login');
    });
    
    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::get('/', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/projects', ManageProjects::class)->name('admin.projects');
        Route::get('/experiences', ManageExperiences::class)->name('admin.experiences');
        Route::get('/skills', ManageSkills::class)->name('admin.skills');
        Route::post('/logout', [AdminLogin::class, 'logout'])->name('admin.logout');
    });
});
