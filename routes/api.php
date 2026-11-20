<?php

use App\Http\Controllers\Api\CvDownloadController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\PushSubscriptionController;
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

Route::post('/push/subscribe', [PushSubscriptionController::class, 'subscribe'])
    ->middleware(['throttle:20,1'])
    ->name('api.push.subscribe');

Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'unsubscribe'])
    ->middleware(['throttle:20,1'])
    ->name('api.push.unsubscribe');
