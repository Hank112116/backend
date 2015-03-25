<?php
return [
    'key'              => env('S3_KEY'),
    'secret'           => env('S3_SECRET'),
    'region'           => env('S3_REGION'),
    'bucket'           => env('S3_BUCKET'),

    'acl'              => 'public-read',

    'endpoint'         => 's3-us-west-2.amazonaws.com',
    'url'              => env('S3_URL'),

    'key_static'       => 'static/',
    'key_origin'       => 'upload/orig/',
    'key_thumb'        => 'upload/thumb/',

    'origin'           => env('S3_URL') . 'upload/orig/',
    'thumb'            => env('S3_URL') . 'upload/thumb/',
    'static'           => env('S3_URL') . 'static/',

    'default_user'     => env('S3_URL') . 'static/no_man.gif',
    'default_project'  => env('S3_URL') . 'static/project_placeholder.jpg',
    'default_solution' => env('S3_URL') . 'static/project_placeholder.jpg',
];
