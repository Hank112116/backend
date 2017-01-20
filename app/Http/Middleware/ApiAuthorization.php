<?php namespace Backend\Http\Middleware;

use Backend\Enums\API\Response\Key\OAuthKey;
use Closure;

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
        if (!session()->get(OAuthKey::ACCESS_TOKEN) or !session()->get(OAuthKey::TOKEN_TYPE)) {
            auth()->logout();
            session()->clear();
            return redirect('/');
        }

        return $next($request);
    }
}
