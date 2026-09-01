<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\ShurjoPayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AdminVendorController;
use App\Http\Controllers\AdminPropertyController;
use App\Http\Controllers\VendorRegistrationController;
use App\Http\Controllers\VendorPropertyController;
use App\Http\Controllers\VendorRoomController;

Route::get('/',fn()=>view('home'))->name('home');\nRoute::get('/search',[HotelSearchController::class,'index'])->name('hotels.search');
Route::get('/hotels/{property:slug}',fn()=>view('hotel-details'))->name('hotel.details');
Route::get('/booking/{booking}/payment',[PublicBookingController::class,'payment'])->name('booking.payment');
Route::get('/booking/{booking}/invoice',[PublicBookingController::class,'invoice'])->name('booking.invoice');
Route::get('/payments/shurjopay/callback',[ShurjoPayController::class,'callback'])->name('shurjopay.callback');
Route::get('/payments/shurjopay/cancel',[ShurjoPayController::class,'cancel'])->name('shurjopay.cancel');

Route::middleware('guest')->group(function(){Route::get('/register',[AuthController::class,'showRegister'])->name('register');Route::post('/register',[AuthController::class,'register']);Route::get('/login',[AuthController::class,'showLogin'])->name('login');Route::post('/login',[AuthController::class,'login']);});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function(){
 Route::get('/email/verify',fn()=>view('auth.verify-email'))->name('verification.notice');
 Route::get('/email/verify/{id}/{hash}',function(EmailVerificationRequest $request){$request->fulfill();return redirect()->route('customer.dashboard');})->middleware('signed')->name('verification.verify');
 Route::post('/email/verification-notification',function(Request $request){$request->user()->sendEmailVerificationNotification();return back()->with('status','verification-link-sent');})->middleware('throttle:6,1')->name('verification.send');
 Route::get('/account',[CustomerDashboardController::class,'index'])->name('customer.dashboard');
 Route::get('/account/bookings/{booking}',[CustomerDashboardController::class,'booking'])->name('customer.booking');
 Route::middleware('admin')->group(function(){
 Route::get('/dashboard/admin',[DashboardController::class,'admin'])->name('dashboard.admin');
 Route::get('/admin/vendors',[AdminVendorController::class,'index'])->name('admin.vendors.index');
 Route::post('/admin/vendors/{vendor}/approve',[AdminVendorController::class,'approve'])->name('admin.vendors.approve');
 Route::post('/admin/vendors/{vendor}/reject',[AdminVendorController::class,'reject'])->name('admin.vendors.reject');
 Route::get('/admin/properties',[AdminPropertyController::class,'index'])->name('admin.properties.index');
 Route::post('/admin/properties/{property}/approve',[AdminPropertyController::class,'approve'])->name('admin.properties.approve');
 Route::post('/admin/properties/{property}/reject',[AdminPropertyController::class,'reject'])->name('admin.properties.reject');
 });
 Route::get('/dashboard/vendor',[CustomerDashboardController::class,'vendor'])->name('vendor.dashboard');
 Route::get('/vendor/register',[VendorRegistrationController::class,'create'])->name('vendor.register');
 Route::post('/vendor/register',[VendorRegistrationController::class,'store'])->name('vendor.register.store');
 Route::get('/vendor/properties',[VendorPropertyController::class,'index'])->name('vendor.properties.index');
 Route::get('/vendor/properties/create',[VendorPropertyController::class,'create'])->name('vendor.properties.create');
 Route::post('/vendor/properties',[VendorPropertyController::class,'store'])->name('vendor.properties.store');
 Route::get('/vendor/properties/{property}/rooms',[VendorRoomController::class,'index'])->name('vendor.rooms.index');
 Route::post('/vendor/properties/{property}/room-types',[VendorRoomController::class,'storeType'])->name('vendor.room-types.store');
 Route::post('/vendor/properties/{property}/rooms',[VendorRoomController::class,'storeRoom'])->name('vendor.rooms.store');
 Route::get('/dashboard/hotel',[DashboardController::class,'hotel'])->name('dashboard.hotel');
});