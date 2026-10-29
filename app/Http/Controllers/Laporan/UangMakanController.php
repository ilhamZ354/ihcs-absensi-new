<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Excel\Exports\SummaryAbsensiExport as Export;
use Auth;
use DB;


class UangMakanController extends DesktopBaseController
{
    protected $route = 'laporan.uang-makan.';

    public function __construct()
    {
    	parent::__construct();
    }

     public function index(Request $request)
    {
        $abs = $this->get_summary($request);

        $date_start = date('Y-m-d', strtotime($request->tahun.'-'.toNumberOfMonth($request->bulan).'-01'));
        $date_end = date('Y-m-d', strtotime($date_start. "+1 month"));
        $max_date = date_diff(date_create($date_start), date_create($date_end))->format("%a");

        $gols = new \stdClass();
        $gols->ALL = 'SEMUA GOLONGAN';        
        foreach (listGroupGolongan() as $key => $value) {
            $gols->{$key} = $value;
        }
        $data = [
          'route' => $this->route,
          'breadcrumb' => ['Laporan' => true, 'Absensi' => route($this->route.'index')],
          // 'units' => UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc')->get(),
          // 'bagians' => [],
          'gols' => $gols,
          'rows' => $abs,
          'max_date' => (int) $max_date,
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

    public function get_summary(Request $request)
    {
        if(empty($request->all())) {
            return collect([]);
        }
        
        $tahun = $request->tahun;
        $bulan = toNumberOfMonth($request->bulan);
        $kodeunitbagian  = $request->nama_unit;
        $kodebagian  = !is_null($request->bagian) ? $request->bagian : '';
        $golongan  = $request->golongan;
        $nama  = !is_null($request->nama_karyawan) ? $request->nama_karyawan : '';

        // dd($tahun, $bulan, $kodeunitbagian, $kodebagian, $golongan, $nama);

        $summaries = DB::select("exec sp_new_get_summary_uang_makan_bulanan ?,?,?,?,?,?", [        
            $tahun
            , $bulan
            , $kodeunitbagian
            , $kodebagian
            , $golongan
            , $nama        
        ]);

        return collect($summaries);        
    }

    public function export(Request $request)
    {
        $collect = collect($this->get_summary($request));
       
        if(count($collect) > 0) {
            $export_header = new \stdClass();
            foreach ($collect->first() as $key => $value) {
                $export_header->{$key} = $this->clean_field($key);
            }
            $merged = collect([$export_header])->merge($collect);

            $tahun = $request->tahun;
            $bulan = toNumberOfMonth($request->bulan);
            $kodeunitbagian  = $request->nama_unit;
            $kodebagian  = !is_null($request->bagian) ? "_" . $request->bagian : "";

            return Excel::download(new Export($merged), "laporan_uang_makan_{$tahun}_{$bulan}_{$kodeunitbagian}_{$kodebagian}.xlsx");
        }
        else {
            return redirect()->route('laporan.absensi.index')
                ->with('swalWarning', 'Tidak ada data yang akan dieksport ke Excel');
        }
    }

    private function clean_field($key)
    {
        switch ($key) {
            case 'id_peserta':
                 return 'PIN';
                break;

            case 'nik_simkar':
                 return 'NIK SIMKAR';
                break;

            case 'nik_sap':
                 return 'NIK SAP';
                break;

            case 'nama_mesin':
                 return 'NAMA PESERTA';
            case 'jl_k' :
                return 'K';
            case 'jl_l' :
                return 'L';
            case 'jl_s' :
                return 'S';
            case 'jl_dl' :
                return 'DL';
            case 'jl_s1' :
                return 'S-1';
            case 'jl_s2' :
                return 'S-2';
            case 'jl_m' :
                return 'M';
            case 'jl_m1' :
                return 'M-1';
            case 'jl_p1' :
                return 'P-1';
            case 'jl_p2' :
                return 'P-2';
            case 'jl_p3' :
                return 'P-3';
            case 'jl_p4' :
                return 'P-4';
            case 'jl_cb' :
                return 'CB';
            case 'jl_ct' :
                return 'CT';
            case 'jl_cp' :
                return 'CP';
            case 'jl_cl' :
                return 'CL';
            case 'jl_h1' :
                return 'H-1';
            case 'jl_h2' :
                return 'H-2';
            case 'jl_h3' :
                return 'H-3';
            case 'jl_ch' :
                return 'CH';
            case 'jl_td' :
                return 'TD';
            case 'jl_na' :
                return 'N/A';
            case 'jl_abs' :
                return 'JLH ABSENSI';

            default:
                return strtoupper($key);
        }
    }
}
