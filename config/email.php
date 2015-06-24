<?php
return [
    'local'       => [
        'user_name'             => env('SES_KEY'),
        'password'              => env('SES_SECRET'),
        'host'                  => env('SES_HOST'),
        'region'                => env('SES_REGION'),
        'charset'               => 'utf-8',
        'smtp_secure'           => 'yls',
        'port'                  => '587',
        'from'                  => 'info@hwtrek.com',
        'from_name'             => 'HWTrek Info',
        'bcc'                   => ['hit112116@hotmail.com'],
        'platform_admin_email'  => 'info@hwtrek.com',
        'system_admin_email'    => 'keith.yeh@hwtrek.com',
        'develop_email'         => 'hank.chang@hwtrek.com',
        'frontPM'               => [9],
        'backendPM'             => [8]
    ],
    'stage'       => [
        'user_name'             => env('SES_KEY'),
        'password'              => env('SES_SECRET'),
        'host'                  => env('SES_HOST'),
        'region'                => env('SES_REGION'),
        'charset'               => 'utf-8',
        'smtp_secure'           => 'yls',
        'port'                  => '587',
        'from'                  => 'info@hwtrek.com',
        'from_name'             => 'HWTrek Info',
        'bcc'                   => ['vivienne.hwtrek@gmail.com'],
        'platform_admin_email'  => 'info@hwtrek.com',
        'system_admin_email'    => 'keith.yeh@hwtrek.com',
        'develop_email'         => 'hank.chang@hwtrek.com',
        'frontPM'               => [12],
        'backendPM'             => [13]
    ],
    'producation' => [
        'user_name'             => env('SES_KEY'),
        'password'              => env('SES_SECRET'),
        'host'                  => env('SES_HOST'),
        'region'                => env('SES_REGION'),
        'charset'               => 'utf-8',
        'smtp_secure'           => 'yls',
        'port'                  => '587',
        'from'                  => 'info@hwtrek.com',
        'from_name'             => 'HWTrek Info',
        'bcc'                   => ['vivienne.hwtrek@gmail.com',
                                    'lucas.wang@hwtrek.com',
                                    'roger.wu@hwtrek.com',
                                    'martin@hwtrek.com'
                                    ],
        'platform_admin_email'  => 'info@hwtrek.com',
        'system_admin_email'    => 'keith.yeh@hwtrek.com',
        'develop_email'         => 'hank.chang@hwtrek.com',
        'frontPM'               => [12,13,14,16],
        'backendPM'             => [2,3,7,8]
    ]
];
