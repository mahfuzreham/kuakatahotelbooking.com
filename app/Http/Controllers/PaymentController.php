<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\ShurjoPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function initiate(Request $request, Booking $booking, ShurjoPayService $shurjoPay)
    {
        abort_unless($booking->status === 'pending_payment', 422, 'Booking cannot be paid.');
        $provider=$request->string('provider','manual')->toString();

        $payment=Payment::create(['booking_id'=>$booking->id,'provider'=>$provider,'amount'=>$booking->total,'currency'=>'BDT','status'=>'initiated']);

        if($provider==='shurjopay'){
            $data=$request->validate(['customer_phone'=>['required','string','max:30'],'customer_address'=>['nullable','string','max:255'],'customer_city'=>['nullable','string','max:100'],'customer_postcode'=>['nullable','string','max:30']]);
            $merchantOrder=$shurjoPay->merchantOrderId($payment->id);
            $payment->update(['reference'=>$merchantOrder]);
            $gateway=$shurjoPay->initiate([
                'return_url'=>route('shurjopay.callback').'?merchant_order_id='.$merchantOrder,
                'cancel_url'=>route('shurjopay.cancel'),
                'amount'=>(string)$booking->total,
                'order_id'=>$merchantOrder,
                'customer_name'=>$booking->guest_name,
                'customer_phone'=>$data['customer_phone'],
                'customer_email'=>$booking->guest_email,
                'customer_address'=>$data['customer_address']??'Bangladesh',
                'customer_city'=>$data['customer_city']??'Kuakata',
                'customer_state'=>$data['customer_city']??'Kuakata',
                'customer_postcode'=>$data['customer_postcode']??'8650',
                'customer_country'=>'BD',
                'client_ip'=>$request->ip(),
                'discount_amount'=>'0','disc_percent'=>'0',
                'shipping_address'=>$data['customer_address']??'Bangladesh',
                'shipping_city'=>$data['customer_city']??'Kuakata',
                'shipping_country'=>'BD',
                'received_person_name'=>$booking->guest_name,
                'shipping_phone_number'=>$data['customer_phone'],
                'value1'=>'Hotel Booking','value2'=>(string)$booking->id,'value3'=>(string)$booking->property_id,'value4'=>'',
            ]);
            $payment->update(['meta'=>['shurjopay_initiation'=>$gateway]]);
            $checkoutUrl=$gateway['checkout_url']??$gateway['payment_url']??$gateway['redirect_url']??null;
            if(!$checkoutUrl) return response()->json(['message'=>'Gateway did not return a checkout URL.','payment'=>$payment],502);
            return response()->json(['payment'=>$payment->fresh(),'redirect_url'=>$checkoutUrl]);
        }

        return response()->json(['payment'=>$payment,'amount'=>$booking->total]);
    }

    public function confirm(Request $request, Payment $payment)
    {
        abort_unless(config('app.env') === 'local' && auth()->check() && auth()->user()->isAdmin(), 403, 'Direct payment confirmation is disabled.');
        DB::transaction(function () use ($payment, $request) {
            $payment->refresh()->lockForUpdate();
            if ($payment->status === 'paid') return;
            $reference=$request->validate(['reference'=>['required','string','max:255']])['reference'];
            $payment->update(['status'=>'paid','reference'=>$reference,'paid_at'=>now()]);
            $payment->booking()->lockForUpdate()->firstOrFail()->update(['status'=>'confirmed']);
        });
        return response()->json(['message'=>'Payment confirmed']);
    }
}