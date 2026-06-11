<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use Illuminate\Support\Facades\Route;

// Auth (tidak perlu token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (perlu token + dicatat log)
Route::middleware(['auth.jwt', 'log.activity'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Province
    Route::get('/province', [ProvinceController::class, 'index']);
    Route::post('/province', [ProvinceController::class, 'store']);
    Route::get('/province/{id}', [ProvinceController::class, 'show']);
    Route::put('/province/{id}', [ProvinceController::class, 'update']);
    Route::delete('/province/{id}', [ProvinceController::class, 'destroy']);

    // City
    Route::get('/city', [CityController::class, 'index']);
    Route::get('/city/province/{province_id}', [CityController::class, 'getByProvince']);
    Route::post('/city', [CityController::class, 'store']);
    Route::get('/city/{id}', [CityController::class, 'show']);
    Route::put('/city/{id}', [CityController::class, 'update']);
    Route::delete('/city/{id}', [CityController::class, 'destroy']);

    // District
    Route::get('/district', [DistrictController::class, 'index']);
    Route::get('/district/city/{city_id}', [DistrictController::class, 'getByCity']);
    Route::post('/district', [DistrictController::class, 'store']);
    Route::get('/district/{id}', [DistrictController::class, 'show']);
    Route::put('/district/{id}', [DistrictController::class, 'update']);
    Route::delete('/district/{id}', [DistrictController::class, 'destroy']);
});
