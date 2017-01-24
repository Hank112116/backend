<?php namespace Backend\Http\Middleware;

use Backend\Enums\API\Response\Key\OAuthKey;
use Closure;
use Cache;

class ApiAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Cache::has(OAuthKey::ACCESS_TOKEN) or !Cache::has(OAuthKey::TOKEN_TYPE)) {
            auth()->logout();

            session()->clear();

            Cache::flush();

            return redirect('/');
        }

        return $next($request);
    }
}
