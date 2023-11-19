<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController; // Import your UrlController if not already imported

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Define a route to handle JSON requests for URLs
Route::post('urls/json', [UrlController::class, 'AddURls']);
Route::get('urls/status/{refrence}', [UrlController::class, 'getUrls']);