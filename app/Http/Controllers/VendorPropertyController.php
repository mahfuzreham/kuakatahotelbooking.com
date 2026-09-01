<?php
namespace App\Http\Controllers;
use App\Models\Property; use App\Models\Vendor; use Illuminate\Http\Request; use Illuminate\Support\Str;
class VendorPropertyController extends Controller {
 private function vendor(Request $r){return Vendor::where('user_id',$r->user()->id)->where('status','approved')->firstOrFail();}
 public function index(Request $r){$vendor=$this->vendor($r);return view('vendor.properties.index',['properties'=>$vendor->properties()->latest()->get()]);}
 public function create(Request $r){$this->vendor($r);return view('vendor.properties.create');}
 public function store(Request $r){$vendor=$this->vendor($r);$d=$r->validate(['name'=>'required|string|max:150','type'=>'required|string|max:50','address'=>'nullable|string','city'=>'required|string|max:100']);$base=Str::slug($d['name']);$slug=$base.'-'.Str::lower(Str::random(6));$vendor->properties()->create($d+['slug'=>$slug,'status'=>'draft','country'=>'Bangladesh']);return redirect()->route('vendor.properties.index')->with('success','Property saved as draft.');}
}