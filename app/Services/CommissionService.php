<?php
namespace App\Services;
use App\Models\CommissionRule;
use App\Models\Property;
class CommissionService {
 public function calculate(Property $property,float $gross): array {
  $rule=CommissionRule::where('is_active',true)->orderByDesc('priority')->first();
  if(!$rule) return ['commission'=>0,'vendor_net'=>$gross,'rule'=>null];
  $commission=$rule->type==='percentage' ? round($gross*((float)$rule->value/100),2) : min((float)$rule->value,$gross);
  return ['commission'=>$commission,'vendor_net'=>round($gross-$commission,2),'rule'=>$rule->id];
 }
}