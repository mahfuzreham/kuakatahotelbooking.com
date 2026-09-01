<?php
namespace App\Services;
use App\Models\Vendor; use App\Models\VendorWallet; use Illuminate\Support\Facades\DB;
class VendorWalletService {
 public function creditPending(Vendor $vendor,float $amount): VendorWallet { return DB::transaction(function()use($vendor,$amount){$w=VendorWallet::firstOrCreate(['vendor_id'=>$vendor->id],['currency'=>'BDT']);$w->lockForUpdate();$w->increment('pending_balance',$amount);return $w->fresh();},3); }
 public function makeAvailable(Vendor $vendor,float $amount): void { DB::transaction(function()use($vendor,$amount){$w=VendorWallet::where('vendor_id',$vendor->id)->lockForUpdate()->firstOrFail();if($w->pending_balance<$amount)abort(422,'Insufficient pending balance.');$w->decrement('pending_balance',$amount);$w->increment('available_balance',$amount);},3); }
}