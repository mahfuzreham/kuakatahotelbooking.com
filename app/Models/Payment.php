<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['booking_id','provider','reference','amount','currency','status','paid_at','meta'];
    protected $casts = ['amount'=>'decimal:2','paid_at'=>'datetime','meta'=>'array'];
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}