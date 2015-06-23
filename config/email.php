<?php
return [
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
    'develop_email'         => 'hank.chang@hwtrek.com'
];
