<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    protected $fillable = ['property_id','name','capacity','base_price','inventory'];

    public function property(): BelongsTo { return $this->belongsTo(Property::class); }

    public function availability(): HasMany { return $this->hasMany(RoomAvailability::class); }
}