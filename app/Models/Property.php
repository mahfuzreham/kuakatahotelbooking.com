<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Property extends Model {
 protected $fillable=['vendor_id','name','slug','type','status','address','city','country'];
 public function vendor():BelongsTo{return $this->belongsTo(Vendor::class);}
 public function roomTypes():HasMany{return $this->hasMany(RoomType::class);}
 public function rooms():HasMany{return $this->hasMany(Room::class);}
 public function bookings():HasMany{return $this->hasMany(Booking::class);}
}