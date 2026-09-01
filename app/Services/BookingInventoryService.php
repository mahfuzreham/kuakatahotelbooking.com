<?php
namespace App\Services;
use App\Models\Booking; use App\Models\RoomAvailability; use Carbon\Carbon; use Illuminate\Support\Facades\DB;
class BookingInventoryService { public function release(Booking $booking): void { DB::transaction(function() use($booking){ foreach($booking->items as $item){ for($d=Carbon::parse($booking->check_in);$d->lt($booking->check_out);$d->addDay()){ RoomAvailability::where('room_type_id',$item->room_type_id)->whereDate('date',$d)->lockForUpdate()->increment('available_inventory',$item->quantity); } } },3); } }