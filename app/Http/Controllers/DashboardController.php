<?php
namespace App\Http\Controllers;
class DashboardController extends Controller {
 public function admin(){return view('dashboard.admin');}
 public function vendor(){return view('dashboard.vendor');}
 public function hotel(){return view('dashboard.hotel');}
}