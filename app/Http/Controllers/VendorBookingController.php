<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorBookingController extends Controller
{
    private function vendor(Request $request): Vendor
    {
        return Vendor::where('user_id', $request->user()->id)->where('status', 'approved')->firstOrFail();
    }

    public function index(Request $request)
    {
        $vendor = $this->vendor($request);
        $propertyIds = $vendor->properties()->pluck('id');
        $query = Booking::with('property')->whereIn('property_id', $propertyIds)->latest();
        if ($request->filled('status')) $query->where('status', $request->string('status')->toString());
        if ($request->filled('q')) {
            $term = trim($request->string('q')->toString());
            $query->where(fn($q) => $q->where('booking_number','like',"%{$term}%")->orWhere('guest_name','like',"%{$term}%")->orWhere('guest_email','like',"%{$term}%"));
        }
        return view('vendor.bookings.index', ['bookings'=>$query->paginate(20)->withQueryString(), 'status'=>$request->string('status')->toString(), 'q'=>$request->string('q')->toString()]);
    }

    public function show(Request $request, Booking $booking)
    {
        $vendor = $this->vendor($request);
        abort_unless($vendor->properties()->whereKey($booking->property_id)->exists(), 403);
        $booking->load(['property','items.roomType','payments']);
        return view('vendor.bookings.show', compact('booking'));
    }
}
