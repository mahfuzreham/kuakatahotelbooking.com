<?php
namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\VendorWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PayoutController extends Controller {
 public function index(Request $request){$vendor=$request->user()->vendor;abort_unless($vendor && $vendor->status==='approved',403);$wallet=VendorWallet::firstOrCreate(['vendor_id'=>$vendor->id],['currency'=>'BDT']);$payouts=Payout::where('vendor_id',$vendor->id)->latest('requested_at')->paginate(15);return view('vendor.payouts.index',compact('wallet','payouts'));}
 public function request(Request $request){$vendor=$request->user()->vendor;abort_unless($vendor && $vendor->status==='approved',403);$d=$request->validate(['amount'=>['required','numeric','min:1'],'method'=>['required','string','max:50']]);DB::transaction(function()use($vendor,$d){$w=VendorWallet::where('vendor_id',$vendor->id)->lockForUpdate()->firstOrFail();abort_if((float)$w->available_balance < (float)$d['amount'],422,'Insufficient balance.');$w->decrement('available_balance',$d['amount']);Payout::create(['vendor_id'=>$vendor->id,'amount'=>$d['amount'],'currency'=>$w->currency,'method'=>$d['method'],'status'=>'requested','requested_at'=>now()]);},3);return redirect()->route('vendor.payouts.index')->with('success','Payout request submitted.');}
}