<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Environments that versioning the content
    |--------------------------------------------------------------------------
    */
    'environments' => ['production', 'stage'],
    'js_path'      => public_path() . '/js/',
    'css_path'     => public_path() . '/css/',
    'react_path'   => public_path() . '/react/',
    'mapping'      => storage_path() . '/app/AssetsMapping.php',
];
