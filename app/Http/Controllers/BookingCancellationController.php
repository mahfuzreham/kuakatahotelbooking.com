<?php
namespace App\Http\Controllers;
use App\Models\Booking;
use App\Services\BookingInventoryService;
use Illuminate\Http\Request;
class BookingCancellationController extends Controller {
 public function cancel(Request $request, Booking $booking, BookingInventoryService $inventory){
  abort_unless($request->user() && ($booking->user_id === $request->user()->id || $request->user()->isAdmin()),403,'You do not have permission to cancel this booking.');
  abort_if(in_array($booking->status,['cancelled','completed','refunded'],true),422,'Booking cannot be cancelled.');
  $inventory->release($booking);
  $booking->update(['status'=>'cancelled']);
  return response()->json(['message'=>'Booking cancelled']);
 }
}