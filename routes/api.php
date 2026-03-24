<?php

use App\Http\Controllers\Api\CvDownloadController;
use App\Http\Controllers\Api\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/cv/download', [CvDownloadController::class, 'download'])
    ->name('api.cv.download');

Route::post('/chatbot', [ChatbotController::class, 'chat'])
    ->middleware('throttle:10,1')
    ->name('api.chatbot');
