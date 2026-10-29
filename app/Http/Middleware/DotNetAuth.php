<?php

namespace App\Http\Middleware;

use Closure;
use Hash;

class DotNetAuth
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
        if($request->has('_token') 
            && password_verify(base64_decode('bmlhc2hhcmFwYW5AZ21haWwuY29t'), $request->_token))
        {
            return $next($request);
        }
        return abort(404);
    }
}
