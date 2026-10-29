<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class BkmMiddleware
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
        if(Auth::user()->hasGroup('ADMIN_ABSENSI') || Auth::user()->hasGroup('SUPER_USER')) {
            return $next($request);            
        } elseif (is_unit_menggunakan_mesin(Auth::user()->getUser()->kode_unit)) {
            return redirect('/')->with('swalError', 'BKM hanya untuk Unit Usaha');
        }
        return $next($request);
    }
}
