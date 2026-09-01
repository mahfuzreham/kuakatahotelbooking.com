<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class CustomerDashboardController extends Controller {
 public function index(Request $request){$bookings=$request->user()->bookings()->with(['property','payments'])->latest()->paginate(15);return view('customer.dashboard',compact('bookings'));}
 public function booking(Request $request,$booking){$booking=$request->user()->bookings()->with(['property','items.roomType','payments'])->findOrFail($booking);return view('customer.booking',compact('booking'));}
}