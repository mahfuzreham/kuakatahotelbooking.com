<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicBookingController;
Route::get('/',fn()=>view('home'))->name('home');
Route::get('/hotels/{property:slug}',fn()=>view('hotel-details'))->name('hotel.details');
Route::get('/booking/{booking}/payment',[PublicBookingController::class,'payment'])->name('booking.payment');
Route::middleware('auth')->group(function(){Route::get('/dashboard/admin',[DashboardController::class,'admin'])->name('dashboard.admin');Route::get('/dashboard/vendor',[DashboardController::class,'vendor'])->name('dashboard.vendor');Route::get('/dashboard/hotel',[DashboardController::class,'hotel'])->name('dashboard.hotel');});