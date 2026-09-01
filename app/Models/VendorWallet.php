<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VendorWallet extends Model { protected $fillable=['vendor_id','pending_balance','available_balance','paid_balance','currency']; protected $casts=['pending_balance'=>'decimal:2','available_balance'=>'decimal:2','paid_balance'=>'decimal:2']; }