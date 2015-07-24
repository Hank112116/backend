<?php
return [
    'host'          => env('LOG_HOST'),
    'port'          => env('LOG_HOST_PORT'),
    'local'         => [
                            'email_title'    => 'Dev backend php error',
                            'email_from'     => 'DevBackend@hwtrek.com',
                            'email_to'       => ['hank.chang@hwtrek.com']
    ],
    'stage'         => [
                            'email_title'    => 'Stage backend php error',
                            'email_from'     => 'StageBackend@hwtrek.com',
                            'email_to'       => [
                                                    'hank.chang@hwtrek.com'
                                                ]
    ],
    'production'    => [
                            'email_title'    => 'Production backend php error',
                            'email_from'     => 'ProductionBackend@hwtrek.com',
                            'email_to'       => [
                                                    'hank.chang@hwtrek.com',
                                                    'keith.yeh@hwtrek.com',
                                                    'vivienne.liao@hwtrek.com'
                                                ]
    ]
];