<?php
return [
    'host'          => env('LOG_HOST'),
    'port'          => env('LOG_HOST_PORT'),
    'slack_token'   => 'xoxp-2849137090-4735103533-7373686018-f59f5e',
    'slack_channel' => '#backendlog',
    'local'         => [
                            'slack_username' => 'Dev Log',
                            'slack_icon'     => ':monkey_face:'
    ],
    'stage'         => [
                            'slack_username' => 'Stage Log',
                            'slack_icon'     => ':ghost:',
    ],
    'production'    => [
                            'slack_username' => 'Production Log',
                            'slack_icon'     => ':bug:',
    ]
];