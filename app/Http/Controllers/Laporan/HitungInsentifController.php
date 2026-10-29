<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Excel\Exports\HitungInsentifExport;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use DB;
use Auth;

class HitungInsentifController extends DesktopBaseController
{
	protected $route = 'laporan.hitung-insentif.';
    protected $userGroups;

    public function __construct()
    {
    	parent::__construct();
    }

    public function index(Request $request)
    {   

        $gols = new \stdClass();
        $gols->ALL = 'SEMUA GOLONGAN';        
        foreach (listGroupGolongan() as $key => $value) {
            $gols->{$key} = $value;
        }

        if($request->all() != []) {
            $exportparams = [
                $request->tahun,
                toNumberOfMonth($request->bulan),
                $request->nama_unit,
                $request->bagian,
                $request->golongan,
                $request->searchBy,
                $request->nama_karyawan,
            ];
        	$insentif = DB::select('exec sp_new_get_laporan_level_insentif_kehadiran_20190723 ?,?,?,?,?,?,?', $exportparams);
        } else {
            $exportparams = [];
            $insentif = [];
        }
        
        $data = [
          'route' => $this->route,
          'breadcrumb' => ['Laporan' => true, 'Absensi' => route($this->route.'index')],
          'insentif' => $insentif,
          'exportparams' => encrypt($exportparams),
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

        if(!empty($data['filter']->nama_unit)) {
            $data['filter']->namaunitbagian = nama_unit($data['filter']->nama_unit);            
        }
        if($request->namaunitbagian == "undefined") {
            $data['filter']->namaunitbagian = null;
        }

        return $this->view($this->route . 'index', $data);
    }

    public function export($_exportparams)
    {
        $exportparams = decrypt($_exportparams);
        $dbRows = collect(DB::select('exec sp_new_get_laporan_level_insentif_kehadiran_20190723 ?,?,?,?,?,?,?', $exportparams));
        $insentif = $dbRows->map(function($row) {
            return collect($row)
                ->only(['tahun', 'bulan', 'nik_simkar', 'nik_sap', 'nama', 'namaunitbagian', 'namabagian'
, 'namajabatan', 'mkg', 'td', 'cp', 'akm_hhmm', 'akm_menit', 'hk', 'level_1', 'level_2', 'level_3', 'level_4', 'level_5', 'level_final'])
                ->all();
        });

        $header = [
            'thn' => 'TAHUN',
            'bln' => 'BULAN',
            'nik_simkar' => 'NIK SIMKAR',
            'nik_sap' => 'NIK SAP',
            'nama_mesin' => 'NAMA',
            'namabagian' => 'BAGIAN',
            'namajabatan' => 'JABATAN',
            'namaunitbagian' => 'NAMA UNIT',
            'mkg' => 'MKG',
            'td' => 'TD',
            'cp' => 'CP',
            'akm_hhmm' => 'AKUMULASI',
            'akm_menit' => 'AKUMULASI (MENIT)',
            'hk' => 'HK',
            'level_1' => 'LEVEL 1',
            'level_2' => 'LEVEL 2',
            'level_3' => 'LEVEL 3',
            'level_4' => 'LEVEL 4',
            'level_5' => 'LEVEL 5',
            'level_final' => 'LEVEL FINAL',
        ];        
        
        $merged = collect([$header])->merge($insentif);
        return Excel::download(new HitungInsentifExport($merged), 'Laporan_IHCS_Perhitungan_Insentif_'.listBulan($exportparams[1]).'_'.$exportparams[0].'.xlsx');
    }
    
}
