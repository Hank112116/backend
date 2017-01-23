<?php namespace Backend\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        '\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            'Backend\Http\Middleware\EncryptCookies',
            'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
            'Illuminate\Session\Middleware\StartSession',
            'Illuminate\View\Middleware\ShareErrorsFromSession',
            'Backend\Http\Middleware\VerifyCsrfToken',
            'Illuminate\Routing\Middleware\SubstituteBindings',
            'Backend\Http\Middleware\CheckDuplicateLogin',
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'api_auth'            => 'Backend\Http\Middleware\ApiAuthorization',
        'auth.basic'          => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest'               => 'Backend\Http\Middleware\RedirectIfAuthenticated',
        'route_filter'        => 'Backend\Http\Middleware\RouteFilter',
        'throttle'            => 'Backend\Http\Middleware\ThrottleMiddleware',
        'check_source_server' => 'Backend\Http\Middleware\AfterCheckSourceServer',
    ];
}
