<?php
namespace App\Http\Controllers\HotelManager;
use App\Http\Controllers\Controller;use App\Models\Booking;use Illuminate\Http\Request;
class BookingController extends Controller {
 public function index(Request $r){
  $propertyId=$r->integer('property_id');
  abort_unless($propertyId,422,'Property is required.');
  $q=Booking::where('property_id',$propertyId)->select(['id','booking_number','property_id','guest_name','check_in','check_out','nights','status','created_at']);
  if($r->filled('status'))$q->where('status',$r->string('status'));
  return $q->latest()->paginate();
 }
 public function show(Request $r,Booking $booking){
  $booking->load(['items.roomType']);
  return response()->json(['booking'=>$booking->only(['id','booking_number','property_id','guest_name','check_in','check_out','nights','status']),'items'=>$booking->items]);
 }
}