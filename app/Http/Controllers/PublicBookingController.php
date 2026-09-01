<?php
namespace App\Http\Controllers;
use App\Models\Booking;
class PublicBookingController extends Controller {
 public function payment(Booking $booking){return view('booking-payment',['booking'=>$booking]);}
}