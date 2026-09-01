<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_number','user_id','property_id','guest_name','guest_email',
        'check_in','check_out','nights','status','total'
    ];

    protected $casts = ['check_in' => 'date', 'check_out' => 'date', 'total' => 'decimal:2'];
}