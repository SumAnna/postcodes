<?php

use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/stores/{postcode}/{radius?}', [StoreController::class, 'getStoresNearPostcode']);

Route::post('/delivery/{postcode}', [StoreController::class, 'getDeliverableStores']);

