<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    'notification_url' => env('NGROK_HTTP_8000') ? env('NGROK_HTTP_8000') . '/api/v1/webhook/midtrans' : env('MIDTRANS_NOTIFICATION_URL'),
];
