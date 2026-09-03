<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;
class RefundController extends Controller {
 public function index(Request $request){
  $q=Refund::with(['booking','payment'])->latest();
  if($request->filled('status')) $q->where('status',$request->status);
  return view('admin.refunds.index',['refunds'=>$q->paginate(20)]);
 }
 public function approve(Refund $refund){abort_if($refund->status!=='pending_review',422);$refund->update(['status'=>'approved']);return back()->with('success','Refund approved');}
 public function reject(Request $request,Refund $refund){abort_if($refund->status!=='pending_review',422);$refund->update(['status'=>'rejected','reason'=>trim(($refund->reason?'Reason: '.$refund->reason.' | ':'').'Admin: '.$request->input('note','Rejected'))]);return back()->with('success','Refund rejected');}
}