<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\VnAddressController;

use App\Http\Controllers\Api\AddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/provinces', [VnAddressController::class, 'provinces']);
Route::get('/districts/{provinceCode}', [VnAddressController::class, 'districts']);
Route::get('/wards/{districtCode}', [VnAddressController::class, 'wards']);

Route::post('/checkout/shipping-fees', [ShippingController::class, 'calculateFees']);

// Address Routes
Route::get('/address/districts', [AddressController::class, 'getDistricts'])->name('api.address.districts');
Route::get('/address/wards', [AddressController::class, 'getWards'])->name('api.address.wards');

// Webhook Routes
Route::post('/webhooks/shipping/ghn', [\App\Http\Controllers\Webhooks\GhnWebhookController::class, 'handle']);
