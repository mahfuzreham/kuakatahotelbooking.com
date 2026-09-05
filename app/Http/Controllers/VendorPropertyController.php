<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RoomAvailability;
use App\Models\RoomType;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorPropertyController extends Controller
{
    private function vendor(Request $request): Vendor
    {
        return Vendor::where('user_id', $request->user()->id)
            ->where('status', 'approved')
            ->firstOrFail();
    }

    private function ownedProperty(Request $request, Property $property): Property
    {
        $vendor = $this->vendor($request);
        abort_unless((int) $property->vendor_id === (int) $vendor->id, 403);

        return $property;
    }

    public function index(Request $request)
    {
        $vendor = $this->vendor($request);

        $properties = $vendor->properties()
            ->withCount(['roomTypes', 'rooms'])
            ->latest()
            ->get();

        return view('vendor.properties.index', compact('properties'));
    }

    public function create(Request $request)
    {
        $this->vendor($request);

        return view('vendor.properties.create');
    }

    public function store(Request $request)
    {
        $vendor = $this->vendor($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:hotel,resort,guest_house'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:100'],
        ]);

        $baseSlug = Str::slug($data['name']) ?: 'property';
        $slug = $baseSlug . '-' . Str::lower(Str::random(8));

        $property = $vendor->properties()->create([
            'name' => $data['name'],
            'slug' => $slug,
            'type' => $data['type'],
            'status' => 'draft',
            'address' => $data['address'] ?? null,
            'city' => $data['city'],
            'country' => 'Bangladesh',
        ]);

        return redirect()
            ->route('vendor.properties.index')
            ->with('success', "Property '{$property->name}' added successfully as draft.");
    }

    public function destroy(Request $request, Property $property)
    {
        $property = $this->ownedProperty($request, $property);

        if ($property->bookings()->exists()) {
            return back()->withErrors([
                'property' => 'This property cannot be deleted because it already has bookings. Cancel/archive the bookings first.',
            ]);
        }

        DB::transaction(function () use ($property) {
            $roomTypeIds = $property->roomTypes()->pluck('id');

            if ($roomTypeIds->isNotEmpty()) {
                RoomAvailability::whereIn('room_type_id', $roomTypeIds)->delete();
            }

            $property->rooms()->delete();
            RoomType::where('property_id', $property->id)->delete();
            $property->delete();
        });

        return redirect()
            ->route('vendor.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}
