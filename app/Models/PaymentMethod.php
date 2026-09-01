<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PaymentMethod extends Model { protected $fillable=['name','code','type','is_active','config']; protected $casts=['is_active'=>'boolean','config'=>'encrypted:array']; }