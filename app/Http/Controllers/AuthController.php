<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller {
 public function showRegister(){return view('auth.register');}
 public function register(Request $request){
  $data=$request->validate(['name'=>['required','string','max:100'],'email'=>['required','email','max:255','unique:users,email'],'phone'=>['nullable','string','max:30','unique:users,phone'],'password'=>['required','confirmed',Password::min(8)]]);
  $user=User::create($data);event(new Registered($user));Auth::login($user);
  return redirect()->route('verification.notice');
 }
 public function showLogin(){return view('auth.login');}
 public function login(Request $request){
  $data=$request->validate(['email'=>['required','email'],'password'=>['required','string'],'remember'=>['nullable','boolean']]);
  if(!Auth::attempt(['email'=>$data['email'],'password'=>$data['password']],(bool)($data['remember']??false)))return back()->withErrors(['email'=>'Invalid email or password.'])->onlyInput('email');
  $request->session()->regenerate();return redirect()->intended(route('customer.dashboard'));
 }
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect('/');}
}