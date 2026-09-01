<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAvailability extends Model
{
    protected $fillable = ['room_type_id','date','available_inventory','price','is_closed','minimum_stay'];
    protected $casts = ['date' => 'date', 'is_closed' => 'boolean', 'price' => 'decimal:2'];

    public function roomType(): BelongsTo { return $this->belongsTo(RoomType::class); }
}