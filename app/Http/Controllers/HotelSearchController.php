<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HotelSearchController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'destination' => ['nullable','string','max:100'],
            'check_in' => ['nullable','date'],
            'check_out' => ['nullable','date','after:check_in'],
            'guests' => ['nullable','string','max:100'],
        ]);

        $hotels = Property::query()
            ->where('status', 'active')
            ->with('roomTypes')
            ->when($filters['destination'] ?? null, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('city', 'like', "%{$term}%")
                      ->orWhere('name', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('search-results', compact('hotels', 'filters'));
    }
}