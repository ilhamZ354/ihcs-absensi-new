<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use App\Services\Excel\Exports\KehadiranIndividuTahunanExport as Export;
use Auth;
use DB;

class LaporanIndividuTahunanController  extends DesktopBaseController
{
    protected $route = 'laporan.kehadiran-individu-tahunan.';

    public function __construct()
    {
    	parent::__construct();
    }

    public function index(Request $request)
    {
    	if(!empty($request->all())) {
    		$request->validate([
    			'tahun' => 'required',
    			'nama_unit' => 'required',
    			'bagian' => 'nullable',
    			'golongan' => 'nullable',
    			'nama_karyawan' => 'required',
    		]);

        	$yearlyAtts = $this->_getKehadiranIndividuTahunan($request);
        	if(!empty($yearlyAtts)) {
        		$merged = collect(array_merge([$this->_columnHeaders()], $yearlyAtts));
        		return Excel::download(new Export($merged), "laporan_kehadiran_individu_tahun_{$request->tahun}_{$request->nama_karyawan}.xlsx");
        	}
        	return redirect()->route($this->route . 'index')->with('swalError','Maaf, data tidak ditemukan. Pastikan pencarian sudah tepat dan coba lagi.')
        	->withInput($request->all());
    	}

    	$gols = new \stdClass();
        $gols->ALL = 'SEMUA GOLONGAN';        
        foreach (listGroupGolongan() as $key => $value) {
            $gols->{$key} = $value;
        }

    	$data = [
          'route' => $this->route,
          'breadcrumb' => ['Laporan' => true, 'Absensi' => route($this->route.'index')],
          'gols' => $gols,
          'filter' => (object) $request->all()
        ];

        if(Auth::user()->getUser()->jenis_user == 'USER_UNIT') {
            $data['units'] = UnitBagianMaster::where('status', 1)->where('kodeunitbagian', Auth::user()->getUser()->kode_unit)->get();
            $bagians = DB::table('tm_bagian')->select('kodebagian', 'namabagian')
            ->where('kelompok', 'UNIT USAHA')
            ->where('isactive', 'Y')
            ->orderBy('namabagian')
            ->get();
            $data['bagians'] = $bagians;
        }else if(Auth::user()->getUser()->jenis_user == 'USER_BAGIAN' && Auth::user()->getUser()->kode_bagian != 'BAG35') {
            $data['units'] = UnitBagianMaster::where('status', 1)->where('kodeunitbagian', Auth::user()->getUser()->kode_unit)->get();
            $data['bagians'] = TmBagian::where('isactive', 'Y')->where('kodebagian', Auth::user()->getUser()->kode_bagian)->get();
        } else {
            $data['units'] = UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc')->get();
            $data['bagians'] = [];
        }

        return $this->view($this->route . 'index', $data);
    }

    private function _getKehadiranIndividuTahunan(Request $request) {

    	$params = [
    		'tahun' => $request->tahun,
    		'nama_unit' => $request->nama_unit,
 			'bagian' => (is_null($request->bagian) || empty($request->bagian)) ? 'ALL' : $request->bagian,
 			'golongan' => (is_null($request->golongan) || empty($request->golongan)) ? 'ALL' : $request->golongan,
 			'nama_karyawan' => $request->nama_karyawan,
    	];

    	return DB::select("exec sp_new_laporan_kehadiran_individu_tahunan :tahun, :nama_unit, :bagian, :golongan, :nama_karyawan", $params);
    }

    private function _columnHeaders() {
    	$headers = [
    		'nik_simkar' => 'NIK SIMKAR',
    		'nik_sap' => 'NIK SAP',
    		'nama' => 'NAMA',
    		'namaunitbagian' => 'UNIT KERJA',
    		'namabagian' => 'BAGIAN',
    		'tgl' => 'TANGGAL',
    		'status' => 'STATUS KEHADIRAN',
    		'on_duty' => 'ON DUTY',
    		'off_duty' => 'OFF DUTY',
    		'checkin' => 'CHECK IN',
    		'checkout' => 'CHECK OUT',
    		'late' => 'TERLAMBAT',
    		'early' => 'CEPAT PULANG',
    	];

    	return json_decode(json_encode($headers));
    }
}
