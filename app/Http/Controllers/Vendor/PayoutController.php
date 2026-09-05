<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Vendor;
use App\Models\VendorWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    private function vendor(Request $request): Vendor
    {
        return Vendor::where('user_id', $request->user()->id)
            ->where('status', 'approved')
            ->firstOrFail();
    }

    public function index(Request $request)
    {
        $vendor = $this->vendor($request);

        $wallet = VendorWallet::firstOrCreate(
            ['vendor_id' => $vendor->id],
            ['currency' => 'BDT']
        );

        $payouts = Payout::where('vendor_id', $vendor->id)
            ->latest('requested_at')
            ->paginate(15);

        return view('vendor.payouts.index', compact('wallet', 'payouts'));
    }

    public function request(Request $request)
    {
        $vendor = $this->vendor($request);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'method' => ['required', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($vendor, $data) {
            $wallet = VendorWallet::where('vendor_id', $vendor->id)
                ->lockForUpdate()
                ->firstOrFail();

            $amount = (float) $data['amount'];

            if ((float) $wallet->available_balance < $amount) {
                abort(422, 'Insufficient balance.');
            }

            $wallet->decrement('available_balance', $amount);
            $wallet->increment('pending_balance', $amount);

            Payout::create([
                'vendor_id' => $vendor->id,
                'amount' => $amount,
                'currency' => $wallet->currency,
                'method' => $data['method'],
                'status' => 'requested',
                'requested_at' => now(),
            ]);
        }, 3);

        return redirect()
            ->route('vendor.payouts.index')
            ->with('success', 'Payout request submitted successfully.');
    }
}
