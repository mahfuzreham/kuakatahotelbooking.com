<?php
namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RefundController {
 public function request(Request $request, Booking $booking){
  abort_unless($request->user() && ($booking->user_id === $request->user()->id || $request->user()->isAdmin()),403,'You do not have permission to request a refund for this booking.');
  $payment=$booking->payments()->where('status','paid')->latest()->firstOrFail();
  $alreadyRefunded=(float)Refund::where('booking_id',$booking->id)->whereIn('status',['pending_review','approved','processing','processed','completed'])->sum('amount');
  $remaining=max(0,(float)$payment->amount-$alreadyRefunded);
  abort_if($remaining<=0,422,'This payment has already been fully refunded or has a pending refund.');
  $d=$request->validate(['amount'=>['required','numeric','min:0.01','max:'.$remaining],'reason'=>['nullable','string','max:1000']]);
  $refund=DB::transaction(fn()=>Refund::create(['booking_id'=>$booking->id,'payment_id'=>$payment->id,'amount'=>$d['amount'],'reason'=>$d['reason']??null,'status'=>'pending_review']));
  return response()->json(['refund'=>$refund],201);
 }
}