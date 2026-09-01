<?php
namespace App\Http\Controllers;
use App\Models\IdentityVerification;use App\Services\IdentityVerificationService;use Illuminate\Http\Request;
class IdentityVerificationController extends Controller {
 public function submit(Request $r,IdentityVerificationService $service){$d=$r->validate(['type'=>['required','in:nid,passport,other'],'document_number'=>['required','string','max:100'],'name'=>['nullable','string','max:255']]);$v=$service->submit($r->user()->id,$d);return response()->json(['verification'=>$v],201);}
 public function mine(Request $r){return IdentityVerification::where('user_id',$r->user()->id)->latest()->get();}
}