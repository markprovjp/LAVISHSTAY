<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPay Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for VNPay payment gateway.
    | You can set these values in your .env file.
    |
    */

    'vnp_url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_return_url' => env('VNPAY_RETURN_URL', 'http://127.0.0.1:8888/vnpay/return'),
    'vnp_tmn_code' => env('VNPAY_TMN_CODE', 'UDOPNWS1'),
    'vnp_hash_secret' => env('VNPAY_HASH_SECRET', 'EBAHADUGCOEWYXCMYZRMTMLSHGKNRPBN'),
    
    // Frontend URL for redirecting after payment
    'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),
    'frontend_payment_url' => env('FRONTEND_PAYMENT_URL', 'http://localhost:3000/payment'),
    
    // Test environment settings
    'test_enabled' => env('VNPAY_TEST_ENABLED', true),
    
    // VNPay test card information
    'test_card' => [
        'bank_code' => 'NCB',
        'card_number' => '9704198526191432198',
        'card_holder' => 'NGUYEN VAN A',
        'expire_date' => '07/15',
        'otp' => '123456'
    ]
];
