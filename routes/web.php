<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminLogin;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\ManageProjects;
use App\Livewire\Admin\ManageExperiences;
use App\Livewire\Admin\ManageSkills;
use App\Livewire\Admin\ProfileSettings;
use App\Livewire\Admin\ManageCertificates;
use App\Livewire\Admin\ManageLanguages;
use App\Livewire\Admin\ActivityLogs;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\BackupController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/projects/{slug}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');

// Debug Proxmox API (only available in local environment)
if (app()->environment('local')) {
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
}

// Admin Routes
Route::prefix('admin')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', AdminLogin::class)->name('admin.login');
        // Rate limit logging in
        Route::post('/login', [AdminLogin::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('admin.login.store');
    });
    
    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::get('/', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/projects', ManageProjects::class)->name('admin.projects');
        Route::get('/experiences', ManageExperiences::class)->name('admin.experiences');
        Route::get('/skills', ManageSkills::class)->name('admin.skills');
        Route::get('/cv-generator', \App\Livewire\Admin\CvGenerator::class)->name('admin.cv-generator');
        Route::get('/ai-cover-letter', \App\Livewire\Admin\AiCoverLetter::class)->name('admin.ai-cover-letter');
        Route::get('/certificates', ManageCertificates::class)->name('admin.certificates');
        Route::get('/languages', ManageLanguages::class)->name('admin.languages');
        Route::get('/activity-logs', ActivityLogs::class)->name('admin.activity-logs');
        Route::get('/seo', \App\Livewire\Admin\SeoManager::class)->name('admin.seo');
        Route::get('/posts', \App\Livewire\Admin\ManagePosts::class)->name('admin.posts');
        
        // Backup & Restore
        Route::get('/backup', function () {
            return view('admin.backup');
        })->name('admin.backup');
        Route::get('/backup/export', [BackupController::class, 'export'])->name('admin.backup.export');
        Route::post('/backup/import', [BackupController::class, 'import'])->name('admin.backup.import');
        
        Route::get('/profile', ProfileSettings::class)->name('admin.profile');
        Route::post('/logout', [AdminLogin::class, 'logout'])->name('admin.logout');
    });
});
