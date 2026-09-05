<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorRoomController extends Controller
{
    private function property(Request $request, Property $property): Property
    {
        $vendor = Vendor::where('user_id', $request->user()->id)
            ->where('status', 'approved')
            ->firstOrFail();

        abort_unless((int) $property->vendor_id === (int) $vendor->id, 403);

        return $property;
    }

    public function index(Request $request, Property $property)
    {
        $property = $this->property($request, $property);
        $property->load(['roomTypes', 'rooms.roomType']);

        return view('vendor.rooms.index', compact('property'));
    }

    public function storeType(Request $request, Property $property)
    {
        $property = $this->property($request, $property);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'capacity' => ['required', 'integer', 'min:1', 'max:30'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'inventory' => ['required', 'integer', 'min:1'],
        ]);

        $property->roomTypes()->create($data);

        return back()->with('success', 'Room type added.');
    }

    public function storeRoom(Request $request, Property $property)
    {
        $property = $this->property($request, $property);

        $data = $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'room_number' => ['required', 'string', 'max:50'],
            'floor' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:available,maintenance,blocked'],
            'notes' => ['nullable', 'string'],
        ]);

        abort_unless(
            RoomType::whereKey($data['room_type_id'])
                ->where('property_id', $property->id)
                ->exists(),
            403
        );

        $property->rooms()->create($data);

        return back()->with('success', 'Room added.');
    }

    public function updateRoom(Request $request, Property $property, Room $room)
    {
        $property = $this->property($request, $property);
        abort_unless((int) $room->property_id === (int) $property->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:available,maintenance,blocked'],
            'floor' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $room->update($data);

        return back()->with('success', 'Room updated.');
    }
}
