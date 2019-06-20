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
        abort(403);

        if (app()->environment() != 'production') {
            return $next($request);
        }

        if($request->user() && in_array($request->user()->id, config('google-calendar.permitted_users'))){
            return $next($request);
        }

        abort(403);
    }
}
