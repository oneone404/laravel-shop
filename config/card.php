<?php

return [
    'base_url'      => env('CARD_BASE_URL', 'https://thegiare.vn'),
    'partner_id'    => env('CARD_PARTNER_ID'),
    'partner_key'   => env('CARD_PARTNER_KEY'),
    'wallet_number' => env('CARD_WALLET_NUMBER'),
    'callback_url'  => env('CARD_CALLBACK_URL'),
    'service_codes' => [
        'ZING' => env('CARD_SERVICE_CODE_ZING', 'Zing'),
    ],
];
