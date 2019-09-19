<?php

namespace onethirtyone\GoogleCalendar\App\Http\Middleware;

use Closure;

class CalendarAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment() != 'production') {
            return $next($request);
        }

        if($request->user() && in_array($request->user()->email, config('google-calendar.permitted_users'))){
            return $next($request);
        }

        abort(403);
    }
}
