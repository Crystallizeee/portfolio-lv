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
