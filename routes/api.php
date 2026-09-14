<?php

use App\Http\Controllers\Api\CvDownloadController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\McpController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// Routes
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/cv/download', [CvDownloadController::class, 'download'])
    ->middleware(['throttle:10,1'])
    ->name('api.cv.download');

Route::post('/chatbot', [ChatbotController::class, 'chat'])
    ->middleware(['throttle:chatbot-short', 'throttle:chatbot-long'])
    ->name('api.chatbot');

// MCP Protocol Routes
// We apply 'auth:api' so it uses Laravel Passport OAuth2
Route::middleware('auth:api')->prefix('mcp')->name('mcp.')->group(function () {
    Route::get('/sse', [McpController::class, 'sse'])->name('sse');
    Route::post('/messages', [McpController::class, 'messages'])->name('messages');
});
