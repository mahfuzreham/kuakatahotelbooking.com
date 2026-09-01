<?php
namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller; use App\Models\Payout; use App\Models\VendorWallet; use Illuminate\Http\Request; use Illuminate\Support\Facades\DB;
class PayoutController extends Controller {
 public function index(Request $request){$vendor=$request->user()->vendor;return response()->json(['wallet'=>VendorWallet::firstOrCreate(['vendor_id'=>$vendor->id],['currency'=>'BDT']),'payouts'=>Payout::where('vendor_id',$vendor->id)->latest()->paginate()]);}
 public function request(Request $request){$vendor=$request->user()->vendor;abort_unless($vendor,403);$d=$request->validate(['amount'=>['required','numeric','min:1'],'method'=>['required','string','max:50']]);$p=DB::transaction(function()use($vendor,$d){$w=VendorWallet::where('vendor_id',$vendor->id)->lockForUpdate()->firstOrFail();abort_if($w->available_balance<$d['amount'],422,'Insufficient balance.');$w->decrement('available_balance',$d['amount']);return Payout::create(['vendor_id'=>$vendor->id,'amount'=>$d['amount'],'currency'=>$w->currency,'method'=>$d['method'],'status'=>'requested','requested_at'=>now()]);},3);return response()->json(['payout'=>$p],201);}
}