<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class IdentityVerification extends Model { protected $fillable=['user_id','type','document_number_hash','document_data','status','provider','provider_reference','reviewed_by','reviewed_at','rejection_reason','meta']; protected $casts=['document_data'=>'encrypted:array','meta'=>'encrypted:array','reviewed_at'=>'datetime']; protected $hidden=['document_data','document_number_hash']; }