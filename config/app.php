<?php

return [

    'url'           => 'http://localhost',

    'timezone'      => 'America/Los_Angeles',

    'locale'        => 'en',
    'fallback_locale' => 'en',

    'hub_token'     => 'fd937f478bec52fa7ab4347eac66e527',
    'hipchat_token' => '0f656c8433c1bc4fbf2fe4ae457208',

    'key'             => env('APP_KEY', 'SomeRandomString'),
    'cipher'          => MCRYPT_RIJNDAEL_128,

    'debug'           => env('APP_DEBUG'),
    'front_domain'    => env('FRONT_DOMAIN', 'www.hwtrek.com'),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog"
    |
    */

    'log'             => 'daily',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers'       => [

        /*
         * Laravel Framework Service Providers...
         */
        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Bus\BusServiceProvider',
        'Illuminate\Cache\CacheServiceProvider',
        'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
        'Illuminate\Routing\ControllerServiceProvider',
        'Illuminate\Cookie\CookieServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Encryption\EncryptionServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Foundation\Providers\FoundationServiceProvider',
        'Illuminate\Hashing\HashServiceProvider',
        'Illuminate\Mail\MailServiceProvider',
        'Illuminate\Pagination\PaginationServiceProvider',
        'Illuminate\Pipeline\PipelineServiceProvider',
        'Illuminate\Queue\QueueServiceProvider',
        'Illuminate\Redis\RedisServiceProvider',
        'Illuminate\Auth\Passwords\PasswordResetServiceProvider',
        'Illuminate\Session\SessionServiceProvider',
        'Illuminate\Translation\TranslationServiceProvider',
        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',


        /*
         * Application Service Providers...
         */
        'Backend\Providers\AppServiceProvider',
        'Backend\Providers\BusServiceProvider',
        'Backend\Providers\ConfigServiceProvider',
        'Backend\Providers\EventServiceProvider',
        'Backend\Providers\RouteServiceProvider',

        /*
         * Third-Party Service Providers...
         */
        'Illuminate\Html\HtmlServiceProvider',
        'Intervention\Image\ImageServiceProvider',
        'Mews\Purifier\PurifierServiceProvider',

        /*
         * Backend Service Providers...
         */
        'Backend\Providers\RepoServiceProvider',
        'Backend\Providers\ApiServiceProvider',
        'Backend\Providers\ModelServiceProvider',
        'Backend\Providers\LoggerServiceProvider',
        'Backend\Providers\FilterServiceProvider',
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases'         => [

        'App'       => 'Illuminate\Support\Facades\App',
        'Artisan'   => 'Illuminate\Support\Facades\Artisan',
        'Auth'      => 'Illuminate\Support\Facades\Auth',
        'Blade'     => 'Illuminate\Support\Facades\Blade',
        'Bus'       => 'Illuminate\Support\Facades\Bus',
        'Cache'     => 'Illuminate\Support\Facades\Cache',
        'Config'    => 'Illuminate\Support\Facades\Config',
        'Cookie'    => 'Illuminate\Support\Facades\Cookie',
        'Crypt'     => 'Illuminate\Support\Facades\Crypt',
        'DB'        => 'Illuminate\Support\Facades\DB',
        'Eloquent'  => 'Illuminate\Database\Eloquent\Model',
        'Event'     => 'Illuminate\Support\Facades\Event',
        'File'      => 'Illuminate\Support\Facades\File',
        'Hash'      => 'Illuminate\Support\Facades\Hash',
        'Input'     => 'Illuminate\Support\Facades\Input',
        'Inspiring' => 'Illuminate\Foundation\Inspiring',
        'Lang'      => 'Illuminate\Support\Facades\Lang',
        'Mail'      => 'Illuminate\Support\Facades\Mail',
        'Password'  => 'Illuminate\Support\Facades\Password',
        'Queue'     => 'Illuminate\Support\Facades\Queue',
        'Redirect'  => 'Illuminate\Support\Facades\Redirect',
        'Redis'     => 'Illuminate\Support\Facades\Redis',
        'Request'   => 'Illuminate\Support\Facades\Request',
        'Response'  => 'Illuminate\Support\Facades\Response',
        'Route'     => 'Illuminate\Support\Facades\Route',
        'Schema'    => 'Illuminate\Support\Facades\Schema',
        'Session'   => 'Illuminate\Support\Facades\Session',
        'Storage'   => 'Illuminate\Support\Facades\Storage',
        'URL'       => 'Illuminate\Support\Facades\URL',
        'Validator' => 'Illuminate\Support\Facades\Validator',
        'View'      => 'Illuminate\Support\Facades\View',

        'Form'      => 'Illuminate\Html\FormFacade',
        'HTML'      => 'Illuminate\Html\HtmlFacade',
        'Log'       => 'Backend\Facades\Log',
        'Carbon'    => 'Carbon\Carbon',
        'Image'     => 'Intervention\Image\Facades\Image',
        'Purifier' => 'Mews\Purifier\Facades\Purifier',
    ],

];
