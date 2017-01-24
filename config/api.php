<?php

return [
    'hwtrek_client_id'     => 'b51a5c41-6238-4976-9e1d-ed48ecc8bbb0',
    'hwtrek_client_secret' => env('HWTREK_CLIENT_SECRET'),

    'client_config'        => [
        'timeout'   => '25.0',
        'cookies'   => true,
        'verify'    => env('CURLOPT_SSL_VERIFYPEER'),
        'headers'   => [
            'X-Requested-With' => 'XMLHttpRequest',
            'Connection'       => 'keep-alive',
            'Referer'          => 'https://' . env('FRONT_DOMAIN', 'www.hwtrek.com'),
        ],
        'http_errors' => false
    ],
    'ttl' => 720, // 6 hours
];
