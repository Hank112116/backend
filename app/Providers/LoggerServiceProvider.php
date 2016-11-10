<?php namespace Backend\Providers;

use Backend\Logger\LogService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class LoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Psr\Log\LoggerInterface',
            function (Application $app) {
                return new LogService(
                    $app['log']->getMonolog()
                );
            }
        );
    }
}
