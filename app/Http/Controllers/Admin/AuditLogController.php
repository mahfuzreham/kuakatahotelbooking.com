<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
class AuditLogController extends Controller {
 public function index(Request $request){
  $q=ActivityLog::with('user')->latest();
  if($request->filled('action')) $q->where('action','like','%'.$request->action.'%');
  if($request->filled('user_id')) $q->where('user_id',$request->user_id);
  return view('admin.audit.index',['logs'=>$q->paginate(30)]);
 }
}