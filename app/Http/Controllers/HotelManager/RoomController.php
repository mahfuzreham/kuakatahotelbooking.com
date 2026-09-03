<?php
namespace App\Http\Controllers\HotelManager;
use App\Http\Controllers\Controller;use App\Models\Property;use App\Models\RoomType;use Illuminate\Http\Request;
class RoomController extends Controller {
 private function authorizeProperty(Request $request,Property $property):void{abort_unless($request->user()?->managesProperty($property->id),403,'You do not manage this property.');}
 public function index(Request $request,Property $property){$this->authorizeProperty($request,$property);return $property->rooms()->with('roomType')->orderBy('room_number')->get();}
 public function store(Request $r,Property $property){
  $this->authorizeProperty($r,$property);
  $d=$r->validate(['room_type_id'=>['required','integer','exists:room_types,id'],'room_number'=>['required','string','max:50'],'floor'=>['nullable','string','max:50'],'status'=>['nullable','in:available,occupied,cleaning,maintenance'],'notes'=>['nullable','string']]);
  $roomType=RoomType::whereKey($d['room_type_id'])->where('property_id',$property->id)->firstOrFail();
  return response()->json($property->rooms()->create($d),201);
 }
}