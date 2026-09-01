<?php
namespace App\Services;
use App\Models\IdentityVerification;use Illuminate\Support\Facades\Hash;
class IdentityVerificationService {
 public function submit(int $userId,array $data):IdentityVerification{return IdentityVerification::create(['user_id'=>$userId,'type'=>$data['type'],'document_number_hash'=>Hash::make($data['document_number']),'document_data'=>['name'=>$data['name']??null,'document_number'=>$data['document_number']],'status'=>'pending','provider'=>$data['provider']??'manual']);}
 public function approve(IdentityVerification $v,int $adminId):IdentityVerification{$v->update(['status'=>'verified','reviewed_by'=>$adminId,'reviewed_at'=>now(),'rejection_reason'=>null]);return $v->fresh();}
 public function reject(IdentityVerification $v,int $adminId,string $reason):IdentityVerification{$v->update(['status'=>'rejected','reviewed_by'=>$adminId,'reviewed_at'=>now(),'rejection_reason'=>$reason]);return $v->fresh();}
}