<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TpPeserta;

class DotNetController extends Controller
{
    function __construct()
    {
    	$this->middleware('dotnet');
    }

    /**
    * Daftar IP mesin absensi PT Perkebunan Nusantara IV
    * @return json 
    */
    public function listMesin()
    {
    	return json_encode(att_machine_list());
    }

    /**
    * Daftar Karyawan SATPAM
    * @return json 
    */
    public function listSatpam()
    {
    	$satpam = TpPeserta::select('pin')->where('satpam', 1)->get()->pluck('pin');
    	return json_encode($satpam);
    }
    
}
