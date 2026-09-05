<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UserRole;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorRegistrationController extends Controller {
 public function create(){return view('vendor.register');}
 public function store(Request $request){
  $data=$request->validate(['business_name'=>'required|string|max:150']);
  DB::transaction(function() use ($request,$data){
   $vendor=Vendor::firstOrCreate(['user_id'=>$request->user()->id],['business_name'=>$data['business_name'],'status'=>'pending','verification_status'=>'unverified']);
   $role=Role::where('slug','vendor')->first();
   if($role) UserRole::firstOrCreate(['user_id'=>$request->user()->id,'role_id'=>$role->id]);
  });
  return redirect()->route('vendor.dashboard')->with('success','Vendor application submitted for admin approval.');
 }
}