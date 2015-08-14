<?php namespace Backend\Providers;

use Route;
use Illuminate\Support\ServiceProvider;
use Backend\Http\Controllers\AuthController;

class FilterServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $controller = AuthController::class;
        Route::filter('backend.login', "{$controller}@loginFilter");
        Route::filter('backend.adminer', "{$controller}@adminerFilter");
        Route::filter('backend.user', "{$controller}@userFilter");
        Route::filter('backend.project', "{$controller}@projectFilter");
        Route::filter('backend.solution', "{$controller}@solutionFilter");
        Route::filter('backend.hub', "{$controller}@hubFilter");
        Route::filter('backend.landing', "{$controller}@landingFilter");
        Route::filter('backend.mail', "{$controller}@mailFilter");
        Route::filter('backend.report', "{$controller}@reportFilter");
    }
}
