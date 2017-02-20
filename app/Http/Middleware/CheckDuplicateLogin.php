<?php

namespace Backend\Http\Middleware;

use Closure;

/**
 * This is the CheckDuplicateLogin middleware class.
 */
class CheckDuplicateLogin
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle($request, Closure $next)
    {
        $session = \DB::table('admin_sessions')->where('id', session()->getId())->first();

        if ($session and $session->user_id === 0) {
            auth()->logout();
            session()->flush();
            session()->flash('login_error_msg', trans()->trans('oauth.duplicate-login'));

            return redirect('/');
        }

        return $next($request);
    }
}
