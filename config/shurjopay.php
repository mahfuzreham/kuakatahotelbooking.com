<?php
return [
 'enabled'=>env('SHURJOPAY_ENABLED',false),
 'sandbox'=>env('SHURJOPAY_SANDBOX',true),
 'base_url'=>env('SHURJOPAY_BASE_URL', env('SHURJOPAY_SANDBOX',true)?'https://sandbox.shurjopayment.com/api':'https://engine.shurjopayment.com/api'),
 'username'=>env('SHURJOPAY_USERNAME'),
 'password'=>env('SHURJOPAY_PASSWORD'),
 'prefix'=>env('SHURJOPAY_PREFIX','KHB'),
 'currency'=>env('SHURJOPAY_CURRENCY','BDT'),
];