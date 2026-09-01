<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;use App\Models\IdentityVerification;use App\Services\IdentityVerificationService;use Illuminate\Http\Request;
class IdentityVerificationController extends Controller {
 public function index(){return IdentityVerification::whereIn('status',['pending','processing'])->latest()->paginate();}
 public function approve(Request $r,IdentityVerification $verification,IdentityVerificationService $service){return response()->json(['verification'=>$service->approve($verification,$r->user()->id)]);}
 public function reject(Request $r,IdentityVerification $verification,IdentityVerificationService $service){$d=$r->validate(['reason'=>['required','string','max:1000']]);return response()->json(['verification'=>$service->reject($verification,$r->user()->id,$d['reason'])]);}
}