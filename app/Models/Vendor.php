<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    protected $fillable = ['user_id','business_name','status','verification_status'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}