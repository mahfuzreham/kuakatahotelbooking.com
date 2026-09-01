<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class ShurjoPayService
{
    protected function baseUrl(): string { return rtrim(config('shurjopay.base_url'),'/'); }

    protected function token(): array
    {
        if(!config('shurjopay.enabled')) throw new RuntimeException('shurjoPay is not enabled.');
        return Cache::remember('shurjopay.token', now()->addMinutes(10), function(){
            $response=Http::acceptJson()->post($this->baseUrl().'/get_token',[
                'username'=>config('shurjopay.username'),'password'=>config('shurjopay.password'),
            ])->throw()->json();
            if(empty($response['token'])) throw new RuntimeException($response['message']??'Unable to authenticate with shurjoPay.');
            return $response;
        });
    }

    public function initiate(array $data): array
    {
        $auth=$this->token();
        $payload=array_merge($data,[
            'prefix'=>config('shurjopay.prefix'),
            'token'=>$auth['token'],
            'store_id'=>$auth['store_id']??null,
            'currency'=>config('shurjopay.currency'),
        ]);
        return Http::withToken($auth['token'],$auth['token_type']??'Bearer')
            ->asMultipart()->post($this->baseUrl().'/secret-pay',$payload)->throw()->json();
    }

    public function verify(string $orderId): array
    {
        $auth=$this->token();
        return Http::withToken($auth['token'],$auth['token_type']??'Bearer')
            ->acceptJson()->post($this->baseUrl().'/verification',['order_id'=>$orderId])->throw()->json();
    }

    public function merchantOrderId(int $paymentId): string
    {
        return substr(config('shurjopay.prefix','KHB').'-'.date('ymd').'-'.$paymentId.'-'.Str::upper(Str::random(4)),0,50);
    }
}