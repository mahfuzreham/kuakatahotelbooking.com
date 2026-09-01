<?php
namespace App\Http\Controllers\HotelManager;
use App\Http\Controllers\Controller;use App\Models\Booking;use App\Models\Room;use Illuminate\Http\Request;use Illuminate\Support\Facades\DB;
class StayController extends Controller {
 public function checkIn(Request $r,Booking $booking){
  $d=$r->validate(['room_id'=>['required','integer','exists:rooms,id']]);
  abort_if($booking->status!=='confirmed',422,'Booking is not ready for check-in.');
  $room=Room::where('id',$d['room_id'])->where('property_id',$booking->property_id)->firstOrFail();
  abort_if($room->status!=='available',422,'Room is not available.');
  DB::transaction(function()use($booking,$room){$booking->update(['status'=>'checked_in']);$room->update(['status'=>'occupied']);});
  return response()->json(['message'=>'Checked in']);
 }
 public function checkOut(Booking $booking){
  abort_if($booking->status!=='checked_in',422,'Booking is not checked in.');
  DB::transaction(function()use($booking){$booking->update(['status'=>'completed']);});
  return response()->json(['message'=>'Checked out']);
 }
}