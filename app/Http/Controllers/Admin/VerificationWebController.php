<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\IdentityVerification;
use App\Services\IdentityVerificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
class VerificationWebController extends Controller {
 public function index(Request $request){
  $q=IdentityVerification::with('user')->latest();
  if($request->filled('status')) $q->where('status',$request->status);
  return view('admin.verifications.index',['verifications'=>$q->paginate(20)]);
 }
 public function approve(Request $request,IdentityVerification $verification,IdentityVerificationService $service){
  $service->approve($verification,$request->user()->id);
  ActivityLogger::log($request->user()->id,'verification.approved',$verification,'Admin approved identity verification');
  return back()->with('success','Verification approved');
 }
 public function reject(Request $request,IdentityVerification $verification,IdentityVerificationService $service){
  $d=$request->validate(['reason'=>['required','string','max:1000']]);
  $service->reject($verification,$request->user()->id,$d['reason']);
  ActivityLogger::log($request->user()->id,'verification.rejected',$verification,'Admin rejected identity verification',['reason'=>$d['reason']]);
  return back()->with('success','Verification rejected');
 }
}