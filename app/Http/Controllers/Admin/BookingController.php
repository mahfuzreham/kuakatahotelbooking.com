<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $q=Booking::with('property')->latest();
        if($s=trim((string)$request->q)) $q->where(fn($x)=>$x->where('booking_number','like',"%$s%")->orWhere('guest_name','like',"%$s%")->orWhere('guest_email','like',"%$s%"));
        if($request->status) $q->where('status',$request->status);
        return view('admin.bookings.index',['bookings'=>$q->paginate(25)->withQueryString()]);
    }
    public function updateStatus(Request $request, Booking $booking)
    {
        $data=$request->validate(['status'=>['required','in:pending_payment,confirmed,cancelled,completed']]);
        $booking->update($data);
        return back()->with('status','Booking status updated.');
    }
}