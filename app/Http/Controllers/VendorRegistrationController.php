<?php
namespace App\Http\Controllers;
use App\Models\Vendor;
use Illuminate\Http\Request;
class VendorRegistrationController extends Controller {
 public function create(){return view('vendor.register');}
 public function store(Request $request){
  $data=$request->validate(['business_name'=>'required|string|max:150']);
  Vendor::firstOrCreate(['user_id'=>$request->user()->id],['business_name'=>$data['business_name'],'status'=>'pending','verification_status'=>'unverified']);
  return redirect()->route('vendor.dashboard')->with('success','Vendor application submitted for admin approval.');
 }
}