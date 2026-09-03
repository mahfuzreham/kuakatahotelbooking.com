<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationWebController extends Controller {
 public function index(){return view('admin.notifications.index',['logs'=>NotificationLog::latest()->paginate(20),'templates'=>NotificationTemplate::latest()->get()]);}

 public function send(Request $request){
  $d=$request->validate([
   'user_id'=>['nullable','exists:users,id'],
   'recipient'=>['nullable','string','max:255'],
   'channel'=>['required','in:email,sms,in_app'],
   'template_code'=>['nullable','string','max:100'],
   'subject'=>['nullable','string','max:255'],
   'message'=>['nullable','string','max:10000'],
  ]);
  $user=$d['user_id']?User::find($d['user_id']):null;
  $recipient=$d['recipient']??$user?->email;
  if(!$recipient) return back()->withErrors(['recipient'=>'Recipient is required.']);

  $template=$d['template_code']?NotificationTemplate::where('code',$d['template_code'])->where('channel',$d['channel'])->where('is_active',true)->first():null;
  $subject=$d['subject']??$template?->subject??'Kuakata Hotel Booking';
  $message=$d['message']??$template?->body??'You have a new notification from Kuakata Hotel Booking.';

  $log=NotificationLog::create(['user_id'=>$user?->id,'channel'=>$d['channel'],'template_code'=>$d['template_code']??'manual','recipient'=>$recipient,'status'=>'queued']);

  try {
   if($d['channel']==='email'){
    Mail::raw($message,function($mail) use($recipient,$subject){$mail->to($recipient)->subject($subject);});
    $log->update(['status'=>'sent','sent_at'=>now()]);
   } elseif($d['channel']==='in_app'){
    $log->update(['status'=>'sent','sent_at'=>now()]);
   } else {
    $log->update(['status'=>'pending_provider','provider_response'=>['message'=>'SMS provider is not configured']]);
   }
   ActivityLogger::log($request->user()->id,'notification.sent',$log,'Admin processed notification',['channel'=>$d['channel'],'recipient'=>$recipient]);
   return back()->with('success','Notification processed successfully.');
  } catch(\Throwable $e) {
   $log->update(['status'=>'failed','provider_response'=>['error'=>$e->getMessage()]]);
   report($e);
   return back()->withErrors(['notification'=>'Notification failed. Check mail configuration and logs.']);
  }
 }
}