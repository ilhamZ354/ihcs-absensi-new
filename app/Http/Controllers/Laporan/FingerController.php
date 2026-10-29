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

class FingerController extends DesktopBaseController
{
    protected $route = 'laporan.finger.';
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
        $gols = new \stdClass();
        $gols->ALL = 'SEMUA GOLONGAN';        
        foreach (listGroupGolongan() as $key => $value) {
            $gols->{$key} = $value;
        }
        try {
            $rows = DB::select("sp_new_generate_laporan_finger_bulanan ?,?,?,?,?,?", [
                $request->tahun
                , toNumberOfMonth($request->bulan)
                , $request->nama_unit
                , $request->bagian
                , $request->golongan
                , $request->nama_karyawan
            ]);
        } catch (QueryException $e) {
            $rows = [];
        }

        $data = [
          'route' => $this->route,
          'breadcrumb' => ['Laporan' => true, 'Finger' => route($this->route.'index')],
          'gols' => $gols,
          'rows' => $rows,
          'filter' => (object) $request->all()
        ];

        if(in_array(Auth::user()->getUser()->jenis_user, ["USER_BAGIAN", "USER_UNIT"])) {
            $data['units'] = UnitBagianMaster::where('kodeunitbagian', $_user->kode_unit)->get();
            $data['bagians'] = [];
        } else {
            $data['units'] = UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc')->get();
        }

        return $this->view($this->route . 'index', $data);
    }

    public function indexRealtime(Request $request)
    {
        try {           
            $rows = DB::select("exec sp_get_detail_user_finger ?,?,?,?,?,?,?", [
                $request->tahun
                , toNumberOfMonth($request->bulan)
                , $request->nama_unit
                , $request->namaunitbagian
                , $request->bagian
                , $request->golongan
                , $request->nama_karyawan
            ]);
        } catch (QueryException $e) {
            $rows = [];
        }

        $_user = Auth::user()->getUser();
        $gols = (object) array_merge(['ALL' => 'SEMUA GOLONGAN'], (array) listGroupGolongan());
        $data = [
          'route' => $this->route,
          'breadcrumb' => ['Laporan' => true, 'Finger' => route($this->route.'index')],
          // 'units' => UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc')->get(),
          // 'bagians' => [],
          'gols' => $gols,
          'rows' => $rows,
          'filter' => (object) $request->all()
        ];

        if(in_array(Auth::user()->getUser()->jenis_user, ["USER_BAGIAN", "USER_UNIT"])) {
            $data['units'] = UnitBagianMaster::where('kodeunitbagian', $_user->kode_unit)->get();
            $data['bagians'] = [];
        } else {
            $data['units'] = UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc')->get();
        }
        return $this->view($this->route . 'realtime', $data);
    }

    public function report($tahun, $bulan, $nik_simkar)
    {
        $exportparams = [$tahun, $bulan, $nik_simkar];
        $tahun = decrypt($tahun);
        $bulan = decrypt($bulan);
        $nik_simkar = decrypt($nik_simkar);

        // dd($tahun, $bulan, $pin);

        $abs = $this->absensi->get_absensi_finger($tahun, $bulan, $nik_simkar, is_nonptpn4($nik_simkar));
        $data = [
          'route' => $this->route,
          'breadcrumb' => ['Laporan' => true, 'Absensi' => route($this->route.'index'), 'Finger' => true],
          'rows' => $abs,
          'exportparams' => $exportparams,
        ];
        return $this->view($this->route . 'report', $data);
    }

    public function export($tahun, $bulan, $pin)
    {
        $absensiController = new AbsensiController();
        return $absensiController->exportfinger($tahun, $bulan, $pin);
    }

    public function exportCustom(Request $request)
    {
        $params = (array) decrypt($request->token);

        $header = [
            'nik_simkar' => 'NIK SIMKAR',
            'nik_sap' => 'NIK SAP',
            'nama' => 'Name',
            'namaunitbagian' => 'NAMA UNIT',
            'namabagian' => 'NAMA BAGIAN',
            'tgl' => 'Date',
            'status' => 'STATUS',
            'jam_masuk' => 'On Duty',
            'jam_pulang' => 'Off Duty',
            'checkin' => 'Clock In',
            'checkout' => 'Clock Out',
            'late' => 'TD',
            'early' => 'CP',
        ];

        $abs = DB::select("exec sp_new_generate_laporan_finger_bulanan :tahun, :bulan, :unit, :bagian, :golongan, :nama", [
            'tahun' => $params['tahun']
            , 'bulan' => toNumberOfMonth($params['bulan'])
            , 'unit' => $params['nama_unit']
            , 'bagian' => $params['bagian']
            , 'golongan' => $params['golongan']
            , 'nama' => $params['nama_karyawan']
        ]);

        $abss = [];
        foreach ($abs as $arr) {
            $temp = new \stdClass();
            foreach ($arr as $key => $value) {
                if($key == 'tgl') {
                    $temp->{$key} = is_null($value) ? null : date('d-m-Y', strtotime($value));
                } else if(in_array($key, ['jam_masuk',
                    'jam_pulang',
                    'checkin',
                    'checkout'])) {
                    $temp->{$key} = is_null($value) ? null : date('H:i', strtotime($value));
                } else {
                    $temp->{$key} = is_null($value) ? null : $value;                    
                }
            }
            array_push($abss, $temp);
        }

        $merged = collect([$header])->merge($abss);        
        return Excel::download(new AbsensiFingerExport($merged), strtoupper("laporan_absensi_finger_{$params['bulan']}_{$params['tahun']}_".nama_unit($params['nama_unit']).".xlsx"));
    }
}
