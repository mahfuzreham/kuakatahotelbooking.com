<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomType extends Model
{
    protected $fillable = ['property_id','name','capacity','base_price','inventory'];
    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
}