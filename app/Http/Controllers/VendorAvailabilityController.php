<?php
namespace App\Http\Controllers;

use App\Models\RoomAvailability;
use App\Models\RoomType;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorAvailabilityController extends Controller
{
    private function roomType(Request $request, RoomType $roomType): RoomType
    {
        $vendor=Vendor::where('user_id',$request->user()->id)->where('status','approved')->firstOrFail();
        abort_unless($roomType->property()->where('vendor_id',$vendor->id)->exists(),403);
        return $roomType;
    }

    public function edit(Request $request, RoomType $roomType)
    {
        $roomType=$this->roomType($request,$roomType);
        $roomType->load('property');
        $from=Carbon::today();
        $availability=$roomType->availability()->whereBetween('date',[$from->toDateString(),$from->copy()->addDays(13)->toDateString()])->orderBy('date')->get();
        return view('vendor.availability',compact('roomType','availability'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $roomType=$this->roomType($request,$roomType);
        $data=$request->validate(['from'=>['required','date'],'to'=>['required','date','after_or_equal:from'],'available_inventory'=>['required','integer','min:0','max:10000'],'price'=>['required','numeric','min:0'],'is_closed'=>['nullable','boolean'],'minimum_stay'=>['required','integer','min:1','max:30']]);
        $from=Carbon::parse($data['from']);$to=Carbon::parse($data['to']);abort_if($from->diffInDays($to)>366,422,'Date range too large.');
        DB::transaction(function() use($roomType,$data,$from,$to){for($date=$from->copy();$date->lte($to);$date->addDay()){RoomAvailability::updateOrCreate(['room_type_id'=>$roomType->id,'date'=>$date->toDateString()],['available_inventory'=>$data['available_inventory'],'price'=>$data['price'],'is_closed'=>(bool)($data['is_closed']??false),'minimum_stay'=>$data['minimum_stay']]);}});
        return redirect()->route('vendor.availability.edit',$roomType)->with('success','Availability updated successfully.');
    }
}
