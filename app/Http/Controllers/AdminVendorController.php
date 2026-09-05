<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UserRole;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVendorController extends Controller {
 public function index(){return view('admin.vendors.index',['vendors'=>Vendor::with('user')->latest()->paginate(20)]);}
 public function approve(Vendor $vendor){
  DB::transaction(function() use ($vendor){
   $vendor->update(['status'=>'approved']);
   $role=Role::where('slug','vendor')->firstOrFail();
   UserRole::firstOrCreate(['user_id'=>$vendor->user_id,'role_id'=>$role->id]);
  });
  return back()->with('success','Vendor approved successfully and vendor access enabled.');
 }
 public function reject(Request $request,Vendor $vendor){$request->validate(['reason'=>'nullable|string|max:1000']);$vendor->update(['status'=>'rejected']);return back()->with('success','Vendor application rejected.');}
}
