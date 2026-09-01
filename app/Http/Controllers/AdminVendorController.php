<?php
namespace App\Http\Controllers;
use App\Models\Vendor;
use Illuminate\Http\Request;
class AdminVendorController extends Controller {
 public function index(){return view('admin.vendors.index',['vendors'=>Vendor::with('user')->latest()->paginate(20)]);}
 public function approve(Vendor $vendor){$vendor->update(['status'=>'approved']);return back()->with('success','Vendor approved successfully.');}
 public function reject(Request $request,Vendor $vendor){$request->validate(['reason'=>'nullable|string|max:1000']);$vendor->update(['status'=>'rejected']);return back()->with('success','Vendor application rejected.');}
}