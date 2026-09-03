<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\ShurjoPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShurjoPayController extends Controller
{
    public function callback(Request $request, ShurjoPayService $gateway)
    {
        $gatewayOrder=$request->string('order_id')->toString();
        $merchantOrder=$request->string('merchant_order_id')->toString();
        $payment=Payment::where('provider','shurjopay')->where('reference',$merchantOrder)->latest()->first();

        if(!$payment && $gatewayOrder){
            $payment=Payment::where('provider','shurjopay')->whereJsonContains('meta->gateway_order_id',$gatewayOrder)->latest()->first();
        }
        if(!$payment) abort(404,'Payment not found.');

        try {
            $verified=$gateway->verify($gatewayOrder ?: $merchantOrder);
            $record=is_array($verified)&&isset($verified[0])?$verified[0]:$verified;
            $success=(int)($record['sp_code']??0)===1000;
            $amount=(float)($record['amount']??0);

            DB::transaction(function()use($payment,$record,$success,$amount){
                $payment=Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
                if($payment->status==='paid'){ return; }
                if($success && abs($amount-(float)$payment->amount)<0.01){
                    $payment->update(['status'=>'paid','paid_at'=>now(),'meta'=>array_merge($payment->meta??[],['shurjopay_verification'=>$record,'gateway_order_id'=>$record['order_id']??null])]);
                    $booking=$payment->booking()->lockForUpdate()->firstOrFail();
                    if($booking->status==='pending_payment') $booking->update(['status'=>'confirmed']);
                } else {
                    $payment->update(['status'=>'failed','meta'=>array_merge($payment->meta??[],['shurjopay_verification'=>$record])]);
                }
            });
            return redirect('/booking/'.$payment->booking_id.'/payment?status='.($success?'success':'failed'));
        } catch(\Throwable $e) {
            Log::warning('shurjoPay verification failed',['payment_id'=>$payment->id,'error'=>$e->getMessage()]);
            return redirect('/booking/'.$payment->booking_id.'/payment?status=pending');
        }
    }

    public function cancel(Request $request)
    {
        return redirect('/')->with('payment_message','Payment was cancelled. You can try again from your booking.');
    }
}