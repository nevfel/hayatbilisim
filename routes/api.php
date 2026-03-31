<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\QuickPaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// İletişim formu için API endpoint
Route::post('/contact', [ContactController::class, 'sendMessage']);

// Link ile ödeme oluşturma (API key korumalı)
Route::post('/quick-payments', [QuickPaymentController::class, 'create'])
    ->middleware('quick_payment_api_key');
