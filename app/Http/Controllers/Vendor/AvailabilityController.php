<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\RoomAvailability;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function bulkUpdate(Request $request, RoomType $roomType)
    {
        abort_unless($roomType->property->vendor_id === $request->user()->vendor?->id, 403);

        $data = $request->validate([
            'from' => ['required','date'],
            'to' => ['required','date','after_or_equal:from'],
            'available_inventory' => ['nullable','integer','min:0'],
            'price' => ['nullable','numeric','min:0'],
            'is_closed' => ['nullable','boolean'],
            'minimum_stay' => ['nullable','integer','min:1'],
        ]);

        $from = Carbon::parse($data['from']);
        $to = Carbon::parse($data['to']);
        abort_if($from->diffInDays($to) > 366, 422, 'Date range too large.');

        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            RoomAvailability::updateOrCreate(
                ['room_type_id' => $roomType->id, 'date' => $date->toDateString()],
                array_filter([
                    'available_inventory' => $data['available_inventory'] ?? $roomType->inventory,
                    'price' => $data['price'] ?? $roomType->base_price,
                    'is_closed' => $data['is_closed'] ?? false,
                    'minimum_stay' => $data['minimum_stay'] ?? 1,
                ], fn($value) => $value !== null)
            );
        }

        return response()->json(['message' => 'Availability updated']);
    }
}