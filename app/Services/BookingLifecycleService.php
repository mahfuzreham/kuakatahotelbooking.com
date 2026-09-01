<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RoomAvailability;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingLifecycleService
{
    public function restoreInventory(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            if (in_array($booking->status, ['cancelled','expired'], true)) return;

            $booking->loadMissing('items.roomType');
            foreach ($booking->items as $item) {
                for ($date = Carbon::parse($booking->check_in); $date->lt($booking->check_out); $date->addDay()) {
                    RoomAvailability::where('room_type_id', $item->room_type_id)
                        ->whereDate('date', $date)
                        ->lockForUpdate()
                        ->increment('available_inventory', $item->quantity);
                }
            }
            $booking->update(['status' => 'cancelled']);
        });
    }
}