<?php

namespace Backend\Http\Middleware;

use Closure;
use Noty;
use GrahamCampbell\Throttle\Throttle;

/**
 * This is the throttle middleware class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ThrottleMiddleware
{
    /**
     * The throttle instance.
     *
     * @var \GrahamCampbell\Throttle\Throttle
     */
    protected $throttle;

    /**
     * Create a new throttle middleware instance.
     *
     * @param \GrahamCampbell\Throttle\Throttle $throttle
     *
     * @return void
     */
    public function __construct(Throttle $throttle)
    {
        $this->throttle = $throttle;
    }

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
        if (!$this->throttle->attempt($request, $limit, $time)) {
            Noty::warn('Too Many Requests');
            return redirect('/');
        }

        return $next($request);
    }
}
