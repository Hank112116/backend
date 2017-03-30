<?php namespace Backend\Providers;

use Backend\Logger\LogService;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Psr\Log\LoggerInterface',
            function () {
                return new LogService(app('log')->getMonolog());
            }
        );
    }
}
