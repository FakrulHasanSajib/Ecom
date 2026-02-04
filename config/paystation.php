<?php



return [

    'merchant_id' => env('PAYSTATION_MERCHANT_ID', '1104-164354'),

    'password' => env('PAYSTATION_PASSWORD', 'gameone'),

    'base_url' => env('PAYSTATION_BASE_URL', 'https://sandbox.paystation.com.bd'),

    // 'api_url' => env('PAYSTATION_API_URL', 'https://api.paystation.com.bd'),

    'callback_url' => env('PAYSTATION_CALLBACK_URL', '/payment/callback'),

];