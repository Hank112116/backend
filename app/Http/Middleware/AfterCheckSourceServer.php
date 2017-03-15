<?php

namespace Backend\Http\Middleware;

use Backend\Enums\API\ApiStatusEnum;
use Backend\Enums\API\Response\Key\OAuthKey;
use Closure;
use Cache;

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
            if (session(OAuthKey::API_SERVER_STATUS) === ApiStatusEnum::STOP_STATUS) {
                $this->flushLoginData();

                session()->flash('login_error_msg', trans()->trans('oauth.source-server-aberrant'));

                return redirect('/');
            }

            if (session(OAuthKey::API_SERVER_STATUS) === ApiStatusEnum::UNAUTHORIZED_STATUS) {
                $this->flushLoginData();

                session()->flash('login_error_msg', trans()->trans('oauth.source-server-unauthorized'));

                return redirect('/');
            }
        }

        return $response;
    }

    private function flushLoginData()
    {
        auth()->logout();
        session()->flush();
        Cache::flush();
    }
}
