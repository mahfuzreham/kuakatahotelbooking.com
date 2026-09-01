<?php
namespace App\Http\Controllers\HotelManager;
use App\Http\Controllers\Controller;use App\Models\Property;use Illuminate\Http\Request;
class RoomController extends Controller {
 public function index(Property $property){return $property->rooms()->with('roomType')->orderBy('room_number')->get();}
 public function store(Request $r,Property $property){$d=$r->validate(['room_type_id'=>['required','integer','exists:room_types,id'],'room_number'=>['required','string','max:50'],'floor'=>['nullable','string','max:50'],'status'=>['nullable','in:available,occupied,cleaning,maintenance'],'notes'=>['nullable','string']]);return response()->json($property->rooms()->create($d),201);}
}