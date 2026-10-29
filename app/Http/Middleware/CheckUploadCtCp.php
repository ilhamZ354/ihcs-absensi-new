<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\TrFileUploadCtcp as Upload;
use Auth;
use DB;

class CheckUploadCtCp
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
        $user = Auth::user()->getUser();

        if ($user->kode_unit != '00') {
            $status = Upload::where('kode_unit', $user->kode_unit)->where('verified_status', 1)->count() > 0;
        } else {
            $status = Upload::where('kode_unit', $user->kode_unit)
            ->where('kode_bagian', $user->kode_bagian)
            ->where('verified_status', 1)
            ->count() > 0;            
        }

        if(!$status && !Auth::user()->hasGroup('ADMIN_ABSENSI')) {
            return redirect()->route('home')->with('swalError', 'Halaman hanya boleh diakses oleh Unit Kerja yang sudah mengupload file CTCP.<br><small>Mohon untuk segera melakukan upload CTCP.</small>');
        }

        return $next($request);
    }
}
