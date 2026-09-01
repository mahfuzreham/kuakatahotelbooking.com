<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    protected $fillable = ['vendor_id','name','slug','type','status','address','city','country'];
    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
}