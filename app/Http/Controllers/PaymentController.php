<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function initiate(Request $request, Booking $booking)
    {
        abort_unless($booking->status === 'pending_payment', 422, 'Booking cannot be paid.');

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'provider' => $request->string('provider', 'manual'),
            'amount' => $booking->total,
            'currency' => 'BDT',
            'status' => 'initiated',
        ]);

        return response()->json(['payment' => $payment, 'amount' => $booking->total]);
    }

    // Gateway webhooks must authenticate signatures before calling this endpoint.
    public function confirm(Request $request, Payment $payment)
    {
        DB::transaction(function () use ($payment, $request) {
            $payment->refresh()->lockForUpdate();
            if ($payment->status === 'paid') return;

            $reference = $request->validate(['reference'=>['required','string','max:255']])['reference'];
            $payment->update(['status'=>'paid','reference'=>$reference,'paid_at'=>now()]);
            $payment->booking()->lockForUpdate()->firstOrFail()->update(['status'=>'confirmed']);
        });

        return response()->json(['message' => 'Payment confirmed']);
    }
}