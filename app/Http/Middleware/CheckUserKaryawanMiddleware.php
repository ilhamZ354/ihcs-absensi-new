<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckUserKaryawanMiddleware
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
        if(!in_array(Auth::user()->getUser()->jenis_user, ['KARYAWAN_PTPN4', 'KARYAWAN_NON_PTPN4']))
            return redirect()->to('/');
            // return abort(404);

        return $next($request);
    }
}
