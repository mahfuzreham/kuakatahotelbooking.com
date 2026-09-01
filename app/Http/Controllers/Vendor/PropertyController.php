<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        return Property::where('vendor_id', $request->user()->vendor?->id)->latest()->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'type' => ['required','string','max:100'],
            'address' => ['nullable','string'],
            'city' => ['required','string','max:100'],
            'country' => ['required','string','max:100'],
        ]);

        $vendor = $request->user()->vendor;
        abort_unless($vendor, 403);

        $data['vendor_id'] = $vendor->id;
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(6));
        return response()->json(Property::create($data), 201);
    }
}