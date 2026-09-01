<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CommissionRule extends Model { protected $fillable=['scope_type','scope_id','type','value','is_active','priority']; protected $casts=['value'=>'decimal:2','is_active'=>'boolean']; }