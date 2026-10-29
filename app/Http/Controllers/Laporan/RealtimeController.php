<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\DesktopBaseController;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Excel\Exports\AbsensiFingerExport;
use App\Models\Simkar\UnitBagianMaster;
use Auth;
use DB;

class RealtimeController extends DesktopBaseController
{
	protected $route = 'laporan.realtime.';
	protected $absensi;

    public function __construct()
    {
    	parent::__construct();
		$this->absensi  = new AbsensiController; 
    }

    private function setUser()
    {
        return Auth::user()->getUser();
    }

    public function index(Request $request)
    {
        $_user = Auth::user()->getUser();
        
    	try {
    		$karyawans = DB::select("select distinct nik, nama, tipe_karyawan from v_karyawan_all where unit = '".$_user->kode_unit."' 
                and bagian = '".$_user->kode_bagian."' order by tipe_karyawan asc, nama asc");
        } catch (QueryException $e) {
            $karyawans = [];
        }

        $data = [
          'route' => $this->route,
          'breadcrumb' => ['Laporan' => true, 'Finger' => route($this->route.'index')],
          // 'units' => UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc')->get(),
          // 'bagians' => [],
          'rows' => [],
          'filter' => (object) $request->all()
        ];
        $data['karyawans'] = $karyawans;


        return $this->view($this->route . 'index', $data);
    }

    public function getTable(Request $request)
    {
        $detailFinger = DB::select('exec sp_get_detail_realtime_finger_by_nik ?',[$request->input('nik')]);
        return response()->json($detailFinger);
    }
}
