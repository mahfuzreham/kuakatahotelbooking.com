<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Http\Request;
class NotificationWebController extends Controller {
 public function index(){return view('admin.notifications.index',['logs'=>NotificationLog::latest()->paginate(20),'templates'=>NotificationTemplate::latest()->get()]);}
 public function send(Request $request){$d=$request->validate(['user_id'=>['nullable','exists:users,id'],'recipient'=>['nullable','string','max:255'],'channel'=>['required','in:email,sms,in_app'],'template_code'=>['nullable','string','max:100']]);$user=$d['user_id']?User::find($d['user_id']):null;NotificationLog::create(['user_id'=>$user?->id,'channel'=>$d['channel'],'template_code'=>$d['template_code']??'manual','recipient'=>$d['recipient']??$user?->email,'status'=>'queued']);return back()->with('success','Notification queued and logged.');}
}