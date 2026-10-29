<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class KuotaController extends Controller
{
    function __construct ()
    {
    	$this->middleware('absensi.api');
    }

    public function cp(Request $request)
    {
    	$nik_simkar = $request->input('nik_simkar');
    	$nik_sap = $request->input('nik_sap');

    	if(!$request->has('nik_simkar') || is_null($request->input('nik_simkar'))) {
    		return $this->errorResult("NIK Simkar tidak boleh kosong, mohon diisi kembali.");
    	}

    	// $details = DB::connection('dev_absensi')->table('tr_kuota_cuti_panjangs')
     //    ->select('nik_simkar', DB::raw('kuota-terpakai as kuota'), 'tgl_mulai', 'tgl_berakhir')
     //    ->where('nik_simkar', $nik_simkar)
     //    ->whereRaw(DB::raw("getdate() between tgl_mulai and tgl_berakhir"))->first();
        $details = DB::connection('dev_absensi')->table('v_karyawan_all')
        ->select(DB::raw("kuota_cuti_panjang as kuota, tgl_mulai_panjang as tgl_mulai,tgl_berakhir_panjang as tgl_berakhir"))
        ->where('nik', $nik_simkar)
        ->whereRaw(DB::raw("getdate() between tgl_mulai_panjang and tgl_berakhir_panjang and status ='01'"))
        ->first();

    	if(!is_null($details) && !empty($details)) {
    		return response()->json([
    			'status' => true,
    			'message' => 'Success', 
    			'data' => $details
    		]);
    	} else {
    		return $this->errorResult('Data cuti panjang kosong');
    	}
    }

    public function ct(Request $request)
    {
    	$nik_simkar = $request->input('nik_simkar');

    	if(!$request->has('nik_simkar') || is_null($request->input('nik_simkar'))) {
    		return $this->errorResult("NIK Simkar tidak boleh kosong, mohon diisi kembali.");
    	}

    	$details = DB::connection('dev_absensi')->table('v_karyawan_all')
        ->select(DB::raw("kuota_cuti_tahunan as kuota, tgl_mulai_tahunan as tgl_mulai,tgl_berakhir_tahunan as tgl_berakhir"))
        ->where('nik', $nik_simkar)
        ->whereRaw(DB::raw("getdate() between tgl_mulai_tahunan and tgl_berakhir_tahunan and status ='01'"))
    	->first();

    	if(!is_null($details) && !empty($details)) {
    		return response()->json([
    			'status' => true,
    			'message' => 'Success', 
    			'data' => $details
    		]);
    	} else {
    		return $this->errorResult('Data cuti tahunan kosong');
    	}
    }

    private function errorResult($message) {
    	return response()->json([
    		'status' => false,
    		'message' => empty($message) ? 'Terjadi kesalahan di sistem Absensi, hubungi Admin.' : $message, 
    	]);
    }
}
