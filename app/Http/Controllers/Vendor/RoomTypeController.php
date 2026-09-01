<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function store(Request $request, Property $property)
    {
        abort_unless($property->vendor_id === $request->user()->vendor?->id, 403);

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'capacity' => ['required','integer','min:1','max:50'],
            'base_price' => ['required','numeric','min:0'],
            'inventory' => ['required','integer','min:1'],
        ]);

        return response()->json($property->roomTypes()->create($data), 201);
    }
}