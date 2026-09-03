<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class BookingController extends Controller {
 public function index(Request $request){$q=Booking::with('property')->latest();if($s=trim((string)$request->q))$q->where(fn($x)=>$x->where('booking_number','like',"%$s%")->orWhere('guest_name','like',"%$s%")->orWhere('guest_email','like',"%$s%"));if($request->status)$q->where('status',$request->status);return view('admin.bookings.index',['bookings'=>$q->paginate(25)->withQueryString()]);}
 public function updateStatus(Request $request, Booking $booking, BookingInventoryService $inventory){
  $data=$request->validate(['status'=>['required','in:pending_payment,confirmed,cancelled,completed']]);
  abort_if(in_array($booking->status,['cancelled','completed','refunded'],true),422,'Finalized booking status cannot be changed.');
  abort_if($data['status']==='completed' && $booking->status!=='checked_in',422,'Only checked-in bookings can be completed.');
  abort_if($data['status']==='confirmed' && $booking->status==='pending_payment' && !$booking->payments()->where('status','paid')->exists(),422,'Cannot confirm booking without a paid payment.');
  DB::transaction(function()use($booking,$data,$inventory){
   $booking->lockForUpdate();
   if($data['status']==='cancelled' && $booking->status!=='cancelled')$inventory->release($booking);
   $booking->update($data);
  });
  return back()->with('status','Booking status updated.');
 }
}