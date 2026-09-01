<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Refund extends Model { protected $fillable=['booking_id','payment_id','amount','reason','status','provider_reference','processed_at','meta']; protected $casts=['amount'=>'decimal:2','processed_at'=>'datetime','meta'=>'array']; }