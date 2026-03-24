<?php

use App\Http\Controllers\Api\CvDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/cv/download', [CvDownloadController::class, 'download'])
    ->name('api.cv.download');
