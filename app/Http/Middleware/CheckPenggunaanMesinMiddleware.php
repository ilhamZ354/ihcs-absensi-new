<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckPenggunaanMesinMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!is_unit_menggunakan_mesin(Auth::user()->getUser()->kode_unit)) {
            return abort('404');
        }
        return $next($request);            
    }
}
