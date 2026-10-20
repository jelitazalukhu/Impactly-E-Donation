<?php

return [

    'certificate_min_amount' => (float) env('CERTIFICATE_MIN_AMOUNT', 50000),

    'midtrans' => [
        'server_key'    => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-demo-dummy-key'),
        'client_key'    => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-demo-dummy-key'),
        'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized'  => true,
        'is_3ds'        => true,
    ],
];
