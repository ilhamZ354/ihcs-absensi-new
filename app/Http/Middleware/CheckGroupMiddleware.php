<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckGroupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $group)
    {
        if(!Auth::user()->hasGroup($group)) {
            return abort(404);
        }
        return $next($request);
    }
}
