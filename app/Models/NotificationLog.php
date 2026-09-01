<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NotificationLog extends Model { protected $fillable=['user_id','booking_id','channel','template_code','recipient','status','provider_response','sent_at']; protected $casts=['sent_at'=>'datetime','provider_response'=>'array']; }