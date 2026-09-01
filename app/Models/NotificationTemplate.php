<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NotificationTemplate extends Model { protected $fillable=['channel','code','subject','body','is_active']; protected $casts=['is_active'=>'boolean']; }