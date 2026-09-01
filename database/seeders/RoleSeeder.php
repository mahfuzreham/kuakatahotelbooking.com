<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
 public function run(): void
 {
  foreach ([
   ['Super Admin','super-admin','Full platform access'],
   ['Admin','admin','Operational platform access'],
   ['Vendor','vendor','Business owner access'],
   ['Hotel Manager','hotel-manager','Property management access'],
   ['Reception','reception','Arrival and room assignment access'],
   ['Housekeeping','housekeeping','Room operations access'],
   ['Customer','customer','Booking customer access'],
  ] as [$name,$slug,$description]) {
   Role::updateOrCreate(['slug'=>$slug], compact('name','description'));
  }
 }
}