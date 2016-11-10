<?php namespace Backend\ErrorReport;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class ErrorReportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Backend\ErrorReport\ReporterInterface',
            function (Application $app) {
                return new Reporter(
                    new Encrypter(config('app.key')),
                    $app->make('Backend\Logger\LoggerInterface')
                );
            }
        );
    }
}
