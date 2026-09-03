<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
class RefundController extends Controller {
 public function index(Request $request){
  $q=Refund::with(['booking','payment'])->latest();
  if($request->filled('status')) $q->where('status',$request->status);
  return view('admin.refunds.index',['refunds'=>$q->paginate(20)]);
 }
 public function approve(Request $request,Refund $refund){abort_if($refund->status!=='pending_review',422);$old=$refund->status;$refund->update(['status'=>'approved']);ActivityLogger::log($request->user()->id,'refund.approved',$refund,'Admin approved refund',['old_status'=>$old,'new_status'=>'approved','amount'=>$refund->amount]);return back()->with('success','Refund approved');}
 public function reject(Request $request,Refund $refund){abort_if($refund->status!=='pending_review',422);$old=$refund->status;$refund->update(['status'=>'rejected','reason'=>trim(($refund->reason?'Reason: '.$refund->reason.' | ':'').'Admin: '.$request->input('note','Rejected'))]);ActivityLogger::log($request->user()->id,'refund.rejected',$refund,'Admin rejected refund',['old_status'=>$old,'new_status'=>'rejected']);return back()->with('success','Refund rejected');}
}