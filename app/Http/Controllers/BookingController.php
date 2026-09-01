<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RoomAvailability;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->validate([
            'property_id' => ['required','integer','exists:properties,id'],
            'check_in' => ['required','date','after_or_equal:today'],
            'check_out' => ['required','date','after:check_in'],
        ]);

        $nights = Carbon::parse($data['check_in'])->diffInDays(Carbon::parse($data['check_out']));

        $rooms = RoomType::where('property_id', $data['property_id'])
            ->with(['availability' => fn ($q) => $q->whereBetween('date', [$data['check_in'], Carbon::parse($data['check_out'])->subDay()->toDateString()])])
            ->get()
            ->filter(fn ($room) => $room->availability->count() === $nights && $room->availability->every(fn ($day) => !$day->is_closed && $day->available_inventory > 0 && $day->minimum_stay <= $nights))
            ->values();

        return response()->json(['nights' => $nights, 'rooms' => $rooms]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => ['required','integer','exists:properties,id'],
            'room_type_id' => ['required','integer','exists:room_types,id'],
            'check_in' => ['required','date','after_or_equal:today'],
            'check_out' => ['required','date','after:check_in'],
            'guest_name' => ['required','string','max:255'],
            'guest_email' => ['required','email','max:255'],
            'guest_phone' => ['nullable','string','max:50'],
        ]);

        return DB::transaction(function () use ($data, $request) {
            $room = RoomType::where('id', $data['room_type_id'])
                ->where('property_id', $data['property_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $from = Carbon::parse($data['check_in']);
            $to = Carbon::parse($data['check_out']);
            $dates = [];
            for ($date = $from->copy(); $date->lt($to); $date->addDay()) $dates[] = $date->toDateString();

            $availability = RoomAvailability::where('room_type_id', $room->id)
                ->whereIn('date', $dates)
                ->lockForUpdate()
                ->get()
                ->keyBy(fn ($row) => $row->date->toDateString());

            if ($availability->count() !== count($dates)) abort(422, 'Availability is not configured for every stay date.');

            $nights = count($dates);
            $total = 0;
            foreach ($dates as $date) {
                $day = $availability[$date];
                if ($day->is_closed || $day->available_inventory < 1 || $day->minimum_stay > $nights) abort(422, 'Selected room is no longer available.');
                $total += $day->price;
            }

            foreach ($availability as $day) {
                $day->decrement('available_inventory');
            }

            $booking = Booking::create([
                'booking_number' => 'KHB-'.strtoupper(Str::random(10)),
                'user_id' => $request->user()?->id,
                'property_id' => $room->property_id,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'nights' => $nights,
                'guest_name' => $data['guest_name'],
                'guest_email' => $data['guest_email'],
                'status' => 'pending_payment',
                'total' => $total,
            ]);

            BookingItem::create(['booking_id'=>$booking->id,'room_type_id'=>$room->id,'quantity'=>1,'unit_price'=>$total]);

            return response()->json([
                'booking' => $booking,
                'guest' => ['name' => $data['guest_name'], 'email' => $data['guest_email']],
            ], 201);
        }, 3);
    }
}