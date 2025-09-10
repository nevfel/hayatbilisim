<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// İletişim formu için API endpoint
Route::post('/contact', [ContactController::class, 'sendMessage']);
