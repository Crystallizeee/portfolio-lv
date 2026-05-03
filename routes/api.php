<?php

use App\Http\Controllers\Api\CvDownloadController;
use App\Http\Controllers\Api\ChatbotController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// AI Chatbot Rate Limiters
//
// Two layers:
//   • chatbot-short : 5 req / 1 minute  per IP   (burst protection)
//   • chatbot-long  : 20 req / 60 minutes per IP  (sustained DoS protection)
//
// This prevents:
//   - Rapid-fire prompt injection probing
//   - API cost abuse via adversarial resource consumption
// ─────────────────────────────────────────────────────────────────────────────
RateLimiter::for('chatbot-short', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip())
        ->response(fn() => response()->json([
            'reply' => "You're sending messages too quickly. Please wait a moment before trying again.",
        ], 429));
});

RateLimiter::for('chatbot-long', function (Request $request) {
    return Limit::perHour(30)->by($request->ip())
        ->response(fn() => response()->json([
            'reply' => "You've reached the hourly limit for the AI assistant. Please try again later.",
        ], 429));
});

// ─────────────────────────────────────────────────────────────────────────────
// Routes
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/cv/download', [CvDownloadController::class, 'download'])
    ->name('api.cv.download');

Route::post('/chatbot', [ChatbotController::class, 'chat'])
    ->middleware(['throttle:chatbot-short', 'throttle:chatbot-long'])
    ->name('api.chatbot');
