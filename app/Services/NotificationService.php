<?php
namespace App\Services;
use App\Models\NotificationLog; use App\Models\NotificationTemplate; use Illuminate\Support\Facades\Mail;
class NotificationService {
 public function send(string $channel,string $code,string $recipient,array $data=[],?int $userId=null,?int $bookingId=null): NotificationLog {
  $template=NotificationTemplate::where(['channel'=>$channel,'code'=>$code,'is_active'=>true])->firstOrFail();
  $body=$this->render($template->body,$data);$subject=$this->render($template->subject??'',$data);
  $log=NotificationLog::create(['user_id'=>$userId,'booking_id'=>$bookingId,'channel'=>$channel,'template_code'=>$code,'recipient'=>$recipient,'status'=>'queued']);
  try { if($channel==='email') Mail::raw($body,fn($m)=>$m->to($recipient)->subject($subject)); else { /* SMS provider adapter */ } $log->update(['status'=>'sent','sent_at'=>now()]); } catch(\Throwable $e){$log->update(['status'=>'failed','provider_response'=>['message'=>$e->getMessage()]]);} return $log;
 }
 private function render(string $text,array $data):string{foreach($data as $k=>$v)$text=str_replace('{{'.$k.'}}',(string)$v,$text);return $text;}
}