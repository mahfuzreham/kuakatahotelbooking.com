<?php
namespace App\Http\Controllers;
use App\Models\Property; use App\Models\Room; use App\Models\RoomType; use App\Models\Vendor; use Illuminate\Http\Request;
class VendorRoomController extends Controller {
 private function property(Request $r,Property $property):Property{$vendor=Vendor::where('user_id',$r->user()->id)->where('status','approved')->firstOrFail();abort_unless($property->vendor_id===$vendor->id,403);return $property;}
 public function index(Request $r,Property $property){$property=$this->property($r,$property);return view('vendor.rooms.index',compact('property'));}
 public function storeType(Request $r,Property $property){$property=$this->property($r,$property);$d=$r->validate(['name'=>'required|max:120','capacity'=>'required|integer|min:1|max:30','base_price'=>'required|numeric|min:0','inventory'=>'required|integer|min:1']);$property->roomTypes()->create($d);return back()->with('success','Room type added.');}
 public function storeRoom(Request $r,Property $property){$property=$this->property($r,$property);$d=$r->validate(['room_type_id'=>'required|exists:room_types,id','room_number'=>'required|max:50','floor'=>'nullable|max:50','status'=>'required|in:available,maintenance,blocked','notes'=>'nullable|string']);abort_unless(RoomType::where('id',$d['room_type_id'])->where('property_id',$property->id)->exists(),403);$property->rooms()->create($d);return back()->with('success','Room added.');}
}