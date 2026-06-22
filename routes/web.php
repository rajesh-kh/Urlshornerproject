<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/short-urls', [\App\Http\Controllers\ShortUrlController::class, 'index']);
    Route::post('/short-urls', [\App\Http\Controllers\ShortUrlController::class, 'store']);
});

// public resolve route intentionally requires auth in controller
Route::get('/s/{slug}', [\App\Http\Controllers\ShortUrlController::class, 'resolve']);
