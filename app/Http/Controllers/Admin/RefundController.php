<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RefundController extends Controller {
 public function index(Request $request){$q=Refund::with(['booking','payment'])->latest();if($request->filled('status'))$q->where('status',$request->status);return view('admin.refunds.index',['refunds'=>$q->paginate(20)]);}
 public function approve(Request $request,Refund $refund){
  DB::transaction(function()use($request,$refund){
   $refund=Refund::whereKey($refund->id)->lockForUpdate()->firstOrFail();
   abort_if($refund->status!=='pending_review',422,'Refund is no longer pending review.');
   $payment=$refund->payment()->lockForUpdate()->firstOrFail();
   abort_if($payment->status!=='paid',422,'Refund payment is not eligible.');
   $used=(float)Refund::where('payment_id',$payment->id)->where('id','!=',$refund->id)->whereIn('status',['approved','processing','processed','completed'])->sum('amount');
   abort_if($used+(float)$refund->amount>(float)$payment->amount+0.0001,422,'Refund exceeds remaining paid amount.');
   $refund->update(['status'=>'approved']);
   ActivityLogger::log($request->user()->id,'refund.approved',$refund,'Admin approved refund',['new_status'=>'approved','amount'=>$refund->amount]);
  });
  return back()->with('success','Refund approved');
 }
 public function reject(Request $request,Refund $refund){
  $refund=Refund::whereKey($refund->id)->lockForUpdate()->firstOrFail();
  abort_if($refund->status!=='pending_review',422,'Refund is no longer pending review.');
  $old=$refund->status;$refund->update(['status'=>'rejected','reason'=>trim(($refund->reason?'Reason: '.$refund->reason.' | ':'').'Admin: '.$request->input('note','Rejected'))]);
  ActivityLogger::log($request->user()->id,'refund.rejected',$refund,'Admin rejected refund',['old_status'=>$old,'new_status'=>'rejected']);
  return back()->with('success','Refund rejected');
 }
}