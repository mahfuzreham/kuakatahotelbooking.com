<?php
namespace App\Http\Controllers;
use App\Models\Booking;
use Illuminate\Http\Request;
class PublicBookingController extends Controller {
 private function authorizeBooking(Request $request, Booking $booking):void{abort_unless($booking->user_id === $request->user()->id || $request->user()->isAdmin(),403,'You do not have permission to access this booking.');}
 public function payment(Request $request,Booking $booking){$this->authorizeBooking($request,$booking);return view('booking-payment',['booking'=>$booking->load('payments')]);}
 public function invoice(Request $request,Booking $booking){$this->authorizeBooking($request,$booking);return view('booking-invoice',['booking'=>$booking->load(['property','items.roomType','payments'])]);}
}