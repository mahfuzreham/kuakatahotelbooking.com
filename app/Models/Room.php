<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Room extends Model {
 protected $fillable=['property_id','room_type_id','room_number','floor','status','notes'];
 public function property():BelongsTo{return $this->belongsTo(Property::class);}
 public function roomType():BelongsTo{return $this->belongsTo(RoomType::class);}
}