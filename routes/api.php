<?php

use Illuminate\Support\Facades\Route;

Route::middleware('upload.sender.auth')->group(function () {
    Route::post('/uploads', [\App\Http\Controllers\UploadController::class, 'store']);
});

Route::post('/uploads/callback', [\App\Http\Controllers\UploadCallbackController::class, 'handle'])
    ->middleware('verify.callback.signature');



