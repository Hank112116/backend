<?php namespace Backend\Providers;

use Backend\Logger\HipChatLogger;
use Config;
use HipChat\HipChat;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class LoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Backend\Logger\LoggerInterface', function (Application $app) {
                return new HipChatLogger(
                    new HipChat(Config::get('app.hipchat_token')),
                    $app->make('auth')
                );
            }
        );
    }
}
