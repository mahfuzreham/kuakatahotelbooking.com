<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller {
 public function showRegister(){return view('auth.register');}
 public function register(Request $request){
  $data=$request->validate(['name'=>['required','string','max:100'],'email'=>['required','email','max:255','unique:users,email'],'phone'=>['nullable','string','max:30','unique:users,phone'],'password'=>['required','confirmed',Password::min(8)]]);
  $user=DB::transaction(function() use($data){$user=User::create($data);$customer=Role::where('slug','customer')->first();if($customer) UserRole::firstOrCreate(['user_id'=>$user->id,'role_id'=>$customer->id]);return $user;});
  event(new Registered($user));Auth::login($user);return redirect()->route('verification.notice');
 }
 public function showLogin(){return view('auth.login');}
 public function login(Request $request){
  $data=$request->validate(['email'=>['required','email'],'password'=>['required','string'],'remember'=>['nullable','boolean']]);
  if(!Auth::attempt(['email'=>$data['email'],'password'=>$data['password']],(bool)($data['remember']??false)))return back()->withErrors(['email'=>'Invalid email or password.'])->onlyInput('email');
  $request->session()->regenerate();$user=$request->user();
  if($user->isAdmin()) return redirect()->route('dashboard.admin');if($user->hasRole('vendor')) return redirect()->route('vendor.dashboard');if($user->hasRole('hotel_manager')) return redirect()->route('dashboard.hotel');return redirect()->route('customer.dashboard');
 }
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('logout.success');}
}