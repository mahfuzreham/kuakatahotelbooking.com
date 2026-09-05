<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Vendor;
use App\Models\VendorWallet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $vendorUser = User::where('email', 'vendor@demo.com')->firstOrFail();
        $vendor = Vendor::updateOrCreate(['user_id' => $vendorUser->id], ['business_name' => 'Kuakata Demo Hospitality','status' => 'approved','verification_status' => 'verified']);
        $properties = [
            ['name'=>'Kuakata Sea View Resort','slug'=>'kuakata-sea-view-resort','type'=>'resort','status'=>'active','address'=>'Sea Beach Road, Kuakata','city'=>'Kuakata','country'=>'Bangladesh','rooms'=>[['name'=>'Deluxe Sea View','capacity'=>2,'base_price'=>4500,'inventory'=>4],['name'=>'Family Suite','capacity'=>4,'base_price'=>7500,'inventory'=>2]]],
            ['name'=>'Kuakata Beach Hotel','slug'=>'kuakata-beach-hotel','type'=>'hotel','status'=>'active','address'=>'Zero Point, Kuakata','city'=>'Kuakata','country'=>'Bangladesh','rooms'=>[['name'=>'Standard Double','capacity'=>2,'base_price'=>2800,'inventory'=>5],['name'=>'Premium Double','capacity'=>2,'base_price'=>3800,'inventory'=>3]]],
        ];
        foreach ($properties as $data) {
            $property=Property::updateOrCreate(['slug'=>$data['slug']],collect($data)->except('rooms')->merge(['vendor_id'=>$vendor->id])->all());
            foreach ($data['rooms'] as $roomData) {
                $roomType=RoomType::updateOrCreate(['property_id'=>$property->id,'name'=>$roomData['name']],$roomData+['property_id'=>$property->id]);
                for($i=1;$i<=$roomData['inventory'];$i++) Room::firstOrCreate(['property_id'=>$property->id,'room_number'=>($roomType->id*100)+$i],['room_type_id'=>$roomType->id,'floor'=>(string)ceil($i/2),'status'=>'available','notes'=>'Demo room']);
            }
        }
        VendorWallet::updateOrCreate(['vendor_id'=>$vendor->id],['pending_balance'=>15000,'available_balance'=>110000,'paid_balance'=>0,'currency'=>'BDT']);
    }
}
