<?php namespace Backend\ErrorReport;

use Config;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class ErrorReportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Backend\ErrorReport\ReporterInterface', function (Application $app) {
                return new Reporter(
                    new Encrypter(Config::get('app.key')),
                    $app->make('Backend\Logger\LoggerInterface')
                );
            }
        );
    }
}
