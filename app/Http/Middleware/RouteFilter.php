<?php

namespace Backend\Http\Middleware;

use Closure;
use Noty;

class RouteFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        $type = explode('|', $type);

        if (auth()->check() and str_contains($request->session()->get('cert'), $type)) {
            return $next($request);
        }

        Noty::warnLang('common.no-permission');

        return redirect('/');
    }
}
