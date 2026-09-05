<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionRule;
use App\Models\Payout;
use App\Models\VendorWallet;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceWebController extends Controller
{
    public function index(){
        return view('admin.finance.index',[
            'walletTotal'=>VendorWallet::sum('available_balance'),
            'pendingPayouts'=>Payout::where('status','requested')->sum('amount'),
            'paidPayouts'=>Payout::where('status','paid')->sum('amount'),
            'rules'=>CommissionRule::orderByDesc('priority')->get(),
            'payouts'=>Payout::latest()->paginate(20),
        ]);
    }

    public function storeRule(Request $request){
        $data=$request->validate([
            'scope_type'=>['required','string','max:100'],
            'scope_id'=>['nullable','integer'],
            'type'=>['required','in:percentage,fixed'],
            'value'=>['required','numeric','min:0'],
            'priority'=>['nullable','integer','min:0'],
        ]);
        $rule=CommissionRule::create($data+['is_active'=>true,'priority'=>$data['priority']??0]);
        ActivityLogger::log($request->user()->id,'commission_rule.created',$rule,'Admin created commission rule',['value'=>$rule->value,'type'=>$rule->type]);
        return back()->with('success','Commission rule added');
    }

    public function processPayout(Request $request,Payout $payout){
        $data=$request->validate(['action'=>['required','in:approve,reject,mark_paid'],'reference'=>['nullable','string','max:255']]);
        DB::transaction(function() use($payout,$data){
            $payout=Payout::whereKey($payout->id)->lockForUpdate()->firstOrFail();
            if($data['action']==='approve'){
                abort_if($payout->status!=='requested',422,'Only requested payouts can be approved.');
                $payout->update(['status'=>'approved']); return;
            }
            if($data['action']==='reject'){
                abort_if(!in_array($payout->status,['requested','approved'],true),422,'Payout cannot be rejected in its current state.');
                $payout->update(['status'=>'rejected']);
                $wallet=VendorWallet::where('vendor_id',$payout->vendor_id)->lockForUpdate()->first();
                if($wallet) $wallet->increment('available_balance',$payout->amount);
                return;
            }
            abort_if($payout->status!=='approved',422,'Payout must be approved before marking it paid.');
            abort_if(blank($data['reference'] ?? null),422,'Payment reference is required.');
            $payout->update(['status'=>'paid','reference'=>$data['reference'],'processed_at'=>now()]);
            $wallet=VendorWallet::where('vendor_id',$payout->vendor_id)->lockForUpdate()->first();
            if($wallet) $wallet->increment('paid_balance',$payout->amount);
        });
        ActivityLogger::log($request->user()->id,'payout.'.$data['action'],$payout,'Admin processed payout',['status'=>$payout->fresh()->status,'amount'=>$payout->amount]);
        return back()->with('success','Payout updated');
    }
}