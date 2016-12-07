<?php

namespace Backend\Http\Middleware;

use Closure;
use Noty;
use Illuminate\Routing\Middleware\ThrottleRequests;

/**
 * This is the throttle middleware class.
 */
class ThrottleMiddleware extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param int $limit
     * @param int $time
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle($request, Closure $next, $limit = 10, $time = 60)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $limit, $time)) {
            session()->flash('login_error_msg', trans()->trans('oauth.to-many-request'));
            return redirect('/');
        }

        $this->limiter->hit($key, $time);

        return $next($request);
    }
}
