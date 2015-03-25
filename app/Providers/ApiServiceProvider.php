<?php namespace Backend\Providers;

use Backend\Api\Lara;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Backend\Api\ApiInterfaces\UserApiInterface', function () {
                return new Lara\UserApi(new \Backend\Model\Eloquent\User());
            }
        );

        $this->app->bind(
            'Backend\Api\ApiInterfaces\CommentApiInterface', function ($app) {
                return new Lara\CommentApi(
                    $app->make('Backend\Repo\RepoInterfaces\CommentInterface')
                );
            }
        );
    }
}
