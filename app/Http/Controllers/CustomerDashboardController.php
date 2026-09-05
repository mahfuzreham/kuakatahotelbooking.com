<?php
namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Vendor;
use Illuminate\Http\Request;
class CustomerDashboardController extends Controller {
 public function index(Request $request){$bookings=$request->user()->bookings()->with(['property','payments'])->latest()->paginate(15);return view('customer.dashboard',compact('bookings'));}
 public function booking(Request $request,$booking){$booking=$request->user()->bookings()->with(['property','items.roomType','payments'])->findOrFail($booking);return view('customer.booking',compact('booking'));}
 public function vendor(Request $request){
  $vendor=Vendor::where('user_id',$request->user()->id)->first();
  $properties=$vendor?->properties();
  $propertyCount=$properties?->count() ?? 0;
  $propertyIds=$vendor?->properties()->pluck('id') ?? collect();
  $bookingQuery=Booking::whereIn('property_id',$propertyIds);
  $bookingCount=$bookingQuery->count();
  $upcomingBookings=$bookingQuery->whereIn('status',['confirmed','checked_in'])->whereDate('check_out','>=',today())->count();
  $bookingValue=$vendor?->properties()->withSum(['bookings'=>fn($q)=>$q->whereIn('status',['confirmed','completed'])],'total')->get()->sum('bookings_sum_total') ?? 0;
  return view('vendor.dashboard',compact('vendor','propertyCount','bookingValue','bookingCount','upcomingBookings'));
 }
}