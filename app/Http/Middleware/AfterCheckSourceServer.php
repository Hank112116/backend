<?php

namespace Backend\Http\Middleware;

use Backend\Enums\API\Response\Key\OAuthKey;
use Closure;

use Noty;

class AfterCheckSourceServer
{
    /**
     * Handle an after the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (session()->has(OAuthKey::API_SERVER_STATUS)) {
            if (session(OAuthKey::API_SERVER_STATUS) === 'stop') {
                auth()->logout();
                session()->clear();

                session()->flash('login_error_msg', trans()->trans('oauth.source-server-aberrant'));

                return redirect('/');
            }
        }

        return $response;
    }
}
