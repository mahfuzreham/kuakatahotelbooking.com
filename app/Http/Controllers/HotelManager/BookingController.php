<?php
namespace App\Http\Controllers\HotelManager;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
class BookingController extends Controller {
 private function authorizeProperty(Request $request,int $propertyId):void{abort_unless($request->user()?->managesProperty($propertyId),403,'You do not manage this property.');}
 public function index(Request $r){
  $propertyId=$r->integer('property_id');
  abort_unless($propertyId,422,'Property is required.');
  $this->authorizeProperty($r,$propertyId);
  $q=Booking::where('property_id',$propertyId)->select(['id','booking_number','property_id','guest_name','check_in','check_out','nights','status','created_at']);
  if($r->filled('status'))$q->where('status',$r->string('status'));
  return $q->latest()->paginate();
 }
 public function show(Request $r,Booking $booking){
  $this->authorizeProperty($r,$booking->property_id);
  $booking->load(['items.roomType']);
  return response()->json(['booking'=>$booking->only(['id','booking_number','property_id','guest_name','check_in','check_out','nights','status']),'items'=>$booking->items]);
 }
}