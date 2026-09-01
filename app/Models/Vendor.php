<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Vendor extends Model {
 protected $fillable=['user_id','business_name','status','verification_status'];
 public function user():BelongsTo{return $this->belongsTo(User::class);}
 public function properties():HasMany{return $this->hasMany(Property::class);}
}