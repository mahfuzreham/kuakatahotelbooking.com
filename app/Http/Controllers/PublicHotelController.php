<?php
namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RoomType;
use Illuminate\Http\Request;

class PublicHotelController extends Controller
{
    public function search(Request $request)
    {
        $data=$request->validate(['destination'=>['nullable','string','max:100']]);
        $q=Property::query()->where('status','active');
        if(!empty($data['destination'])){
            $term=$data['destination'];
            $q->where(function($x)use($term){$x->where('city','like',"%{$term}%")->orWhere('name','like',"%{$term}%");});
        }
        return response()->json(['hotels'=>$q->with('roomTypes')->orderBy('name')->paginate(20)]);
    }

    public function show(Property $property)
    {
        abort_unless($property->status==='active',404);
        return response()->json(['hotel'=>$property->load('roomTypes')]);
    }

    public function rooms(Request $request, Property $property)
    {
        $data=$request->validate([
            'check_in'=>['required','date','after_or_equal:today'],
            'check_out'=>['required','date','after:check_in'],
        ]);
        $request->merge(['property_id' => $property->id]);
        return app(BookingController::class)->search($request);
    }
}