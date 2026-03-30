<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\VnAddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/provinces', [VnAddressController::class, 'provinces']);
Route::get('/districts/{provinceCode}', [VnAddressController::class, 'districts']);
Route::get('/wards/{districtCode}', [VnAddressController::class, 'wards']);

Route::post('/checkout/shipping-fees', [ShippingController::class, 'calculateFees']);
