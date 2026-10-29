<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Log;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Services\Absensi\Exceptions\ApiAbsensiException;

class ApiProtectionMiddleware
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
        $fulltoken = $request->header('Authorization');
        $token = str_replace('bearer ', '', $fulltoken);
        try {
            if(!empty($request->header('Authorization'))) {
                
                $username = ihcs_decrypt($token);
                if($this->user_verify($username)) {
                    return $next($request);
                }
                Log::error("Username tidak dapat diverifikasi");
                return abort(404);
            }
        } catch (DecryptException $e) {
            Log::error("API Token tidak valid");
            throw new ApiAbsensiException("API Token tidak valid", 1003);            
        } catch (Exception $e) {
            Log::error("Gagal mengautentikasi API, hubungi admin");
            throw new ApiAbsensiException("Gagal mengautentikasi API, hubungi admin", 1004);
        }        
        Log::error("Semua prosedure dari ". __method__." gagal.");
        return abort(404);
    }

    private function user_verify($username):bool
    {
        Log::info('Api access from '. $username);
        $user = DB::connection('sso')->table('ts_sessions as s')
                // ->select('u.nik','u.username','u.nama', 's.id_session')
                ->join('tr_users as u', 'u.id_user', '=', 's.id_user')
                ->where('s.row_status', 1)
                ->where('s.expired', 0)
                ->where('s.wk_expired', '>=', DB::raw("getdate()"))
                ->where('u.username', $username)
                ->count();
        Log::info("User status = " . $user);
        return $user > 0;
    }
}
