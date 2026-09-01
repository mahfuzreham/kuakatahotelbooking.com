<?php
namespace App\Http\Controllers;
use App\Models\Property;
class AdminPropertyController extends Controller {
 public function index(){return view('admin.properties.index',['properties'=>Property::with('vendor.user')->latest()->paginate(20)]);}
 public function approve(Property $property){$property->update(['status'=>'active']);return back()->with('success','Property approved and published.');}
 public function reject(Property $property){$property->update(['status'=>'rejected']);return back()->with('success','Property rejected.');}
}