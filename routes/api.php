<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShippingController;

use App\Http\Controllers\Api\AddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/checkout/shipping-fees', [ShippingController::class, 'calculateFees']);

// Address Routes
Route::get('/address/districts', [AddressController::class, 'getDistricts'])->name('api.address.districts');
Route::get('/address/wards', [AddressController::class, 'getWards'])->name('api.address.wards');
