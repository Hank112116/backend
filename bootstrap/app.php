<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
	realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
	'Illuminate\Contracts\Http\Kernel',
	'Backend\Http\Kernel'
);

$app->singleton(
	'Illuminate\Contracts\Console\Kernel',
	'Backend\Console\Kernel'
);

$app->singleton(
	'Illuminate\Contracts\Debug\ExceptionHandler',
	'Backend\Exceptions\Handler'
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

//see:http://monolog-api.richardjh.org/namespace-Monolog.html
$app->configureMonologUsing(function(\Monolog\Logger $monolog) {
    //error log to syslog server
    $syslog = new \Monolog\Handler\SyslogUdpHandler(
            config('syslog.host'),
            config('syslog.port')
        );
    $formatter = new \Monolog\Formatter\JsonFormatter('%channel%.%level_name%: %message% %extra%');
    $processor = new \Monolog\Processor\TagProcessor(['backend_laravel_log']);

    $syslog->setFormatter($formatter);

    $monolog->pushProcessor($processor);
    $monolog->pushHandler($syslog);

    //error log to mail
    $env = env('APP_ENV');
    if( $env != 'local') {
        $mailerHandler = new \Monolog\Handler\NativeMailerHandler(
                    config("syslog.{$env}.email_to"),
                    config("syslog.{$env}.email_title"),
                    config("syslog.{$env}.email_from")
            );
        $monolog->pushHandler($mailerHandler);
    }
});

return $app;
