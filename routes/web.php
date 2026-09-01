<?php
use Illuminate\Support\Facades\Route;use App\Http\Controllers\DashboardController;
Route::get('/',fn()=>view('home'))->name('home');
Route::get('/hotels/{slug?}',fn()=>view('hotel-details'))->name('hotel.details');
Route::middleware('auth')->group(function(){Route::get('/dashboard/admin',[DashboardController::class,'admin'])->name('dashboard.admin');Route::get('/dashboard/vendor',[DashboardController::class,'vendor'])->name('dashboard.vendor');Route::get('/dashboard/hotel',[DashboardController::class,'hotel'])->name('dashboard.hotel');});