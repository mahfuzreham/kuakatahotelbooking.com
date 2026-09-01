<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payout extends Model { protected $fillable=['vendor_id','amount','currency','method','status','reference','requested_at','processed_at','meta']; protected $casts=['amount'=>'decimal:2','requested_at'=>'datetime','processed_at'=>'datetime','meta'=>'array']; }