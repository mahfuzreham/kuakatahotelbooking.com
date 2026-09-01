<?php

use App\Http\Controllers\Vendor\AvailabilityController;
use App\Http\Controllers\Vendor\PropertyController;
use App\Http\Controllers\Vendor\RoomTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('vendor')->group(function () {
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::post('/properties/{property}/room-types', [RoomTypeController::class, 'store']);
    Route::post('/room-types/{roomType}/availability/bulk', [AvailabilityController::class, 'bulkUpdate']);
});