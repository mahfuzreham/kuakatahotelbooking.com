<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable implements MustVerifyEmail {
 use HasFactory,Notifiable;
 protected $fillable=['name','email','phone','password'];
 protected $hidden=['password','remember_token'];
 protected function casts():array{return ['email_verified_at'=>'datetime','password'=>'hashed'];}
 public function bookings():HasMany{return $this->hasMany(Booking::class);}
 public function roles():HasMany{return $this->hasMany(UserRole::class);}
 public function isAdmin():bool{return $this->roles()->whereHas('role',fn($q)=>$q->where('slug','admin'))->exists();}
}