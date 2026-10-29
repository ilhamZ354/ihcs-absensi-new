<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Excel\Exports\SummaryAbsensiExport as Export;
use App\Services\Excel\Exports\AbsensiFingerExport;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;

class AbsensiController extends DesktopBaseController
{
	protected $route = 'laporan.absensi.';

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

        // $gols = (object) array_merge(['ALL' => 'SEMUA GOLONGAN'], (array) listGroupGolongan());
        $gols = new \stdClass();
        $gols->ALL = 'SEMUA GOLONGAN';        
        foreach (listGroupGolongan() as $key => $value) {
            $gols->{$key} = $value;
        }
        $data = [
          'route' => $this->route,
          'breadcrumb' => [
            'Laporan' => true, 
            'Absensi' => route($this->route.'index')
        ],
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
            $data['units'] = UnitBagianMaster::where('status', 1)->orWhere('kodeunitbagian','04')->orderBy('kodeunitbagian', 'asc')->get();
            $data['bagians'] = [];
        }

        Log::info('data', ['data' => $data]);

        if(!empty($data['filter']->nama_unit)) {
            $data['filter']->namaunitbagian = nama_unit($data['filter']->nama_unit);            
        }
        if($request->namaunitbagian == "undefined") {
            $data['filter']->namaunitbagian = null;
        }

        
        return $this->view($this->route . 'index', $data);
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


            $tahun = "_".$request->tahun;
            $bulan = "_".$request->bulan;
            $kodeunitbagian  = "_".$request->nama_unit;
            $kodebagian  = !is_null($request->bagian) ? "_".$request->bagian : "";
            $golongan  = "_".$request->golongan;
            $nama  = !empty($request->nama_karyawan) ? "_".$request->nama_karyawan : "";

            return Excel::download(new Export($merged), "laporan_absensi{$tahun}{$bulan}{$kodeunitbagian}{$kodebagian}{$golongan}{$nama}.xlsx");
        }
        else {
            return redirect()->route('laporan.absensi.index')
                ->with('swalWarning', 'Tidak ada data yang akan dieksport ke Excel');
        }
    }

    public function get_summary(Request $request)
    {
        if(empty($request->all())) {
            return collect([]);
        }
        
        $tahun = $request->tahun;
        $bulan = toNumberOfMonth($request->bulan);
        $kodeunitbagian  = $request->nama_unit;
        $kodebagian  = !is_null($request->bagian) ? $request->bagian : null;
        $golongan  = $request->golongan;
        $nama  = !empty($request->nama_karyawan) ? $request->nama_karyawan : null;
        $summaries = DB::select("exec sp_new_get_summary_bulanan ?,?,?,?,?,?", [        
            $tahun
            , $bulan
            , $kodeunitbagian
            , $kodebagian
            , $golongan
            , $nama        
        ]);

        return collect($summaries);        
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

    public function finger($tahun, $bulan, $pin)
    {
        $exportparams = [$tahun, $bulan, $pin];
        $tahun = decrypt($tahun);
        $bulan = decrypt($bulan);
        $pin = decrypt($pin);

        $abs = $this->get_absensi_finger($tahun, $bulan, $pin, is_nonptpn4($pin));
        $data = [
          'route' => $this->route,
          'breadcrumb' => [
            'Laporan' => true, 
            'Absensi' => route($this->route.'index'), 
            'Finger' => true,
        ],
          'rows' => $abs,
          'exportparams' => $exportparams,
        ];
        return $this->view($this->route . 'finger', $data);
    }

    public function get_absensi_finger($tahun, $bulan, $nik_simkar, $is_honor)
    {
        $data = DB::select("exec sp_new_generate_laporan_finger_bulanan_individu :tahun, :bulan, :nik_simkar", [
            $tahun, $bulan, $nik_simkar
        ]);
        // $data = DB::table("new_detail_absensi_mesins as ad")
        // ->select('ad.nik_simkar', 'k.nik_sap', 'ad.nama', 'u.namaunitbagian', 'b.namabagian', /*'j.jabatan', */'ad.tgl', 'ad.status', 'ad.checkin', 'ad.checkout', 'ad.late', 'ad.early')
        // ->join('v_history_karyawan as k', function ($join){
        //     $join->on('k.nik', '=', 'ad.nik_simkar')
        //     ->on('k.tahun', '=', DB::raw('year(ad.tgl)'))
        //     ->on('k.bulan', '=', DB::raw('month(ad.tgl)'));
        // })
        // ->join('UnitBagianMaster as u', 'u.kodeunitbagian', 'ad.unit')
        // ->join('tm_bagian as b', 'b.kodebagian', 'ad.kodebagian')
        // // ->join('tm_jabatan as j', 'j.kodejabatan', 'ad.kodejabatan')
        // ->where('ad.nik_simkar', $nik_simkar)
        // ->where(DB::raw("year(ad.tgl)"), $tahun)
        // ->where(DB::raw("month(ad.tgl)"), $bulan)
        // ->orderBy('ad.tgl', 'asc')
        // ->get();
        // dd($data);

        return $data;
    }

    public function exportfinger($tahun, $bulan, $pin)
    {
        $tahun = decrypt($tahun);
        $bulan = decrypt($bulan);
        $pin = decrypt($pin);


        $header = [
            'nik_simkar' => 'NIK SIMKAR',
            'nik_sap' => 'NIK SAP',
            'nama' => 'Name',
            'namabagian' => 'NAMA BAGIAN',
            'namaunitbagian' => 'NAMA UNIT',
            'tgl' => 'Date',
            'status' => 'STATUS',
            'jam_masuk' => 'On Duty',
            'jam_pulang' => 'Off Duty',
            'checkin' => 'Clock In',
            'checkout' => 'Clock Out',
            'late' => 'TD',
            'early' => 'CP',
        ];        
        $abs = $this->get_absensi_finger($tahun, $bulan, $pin, is_nonptpn4($pin));
        $abss = [];
        foreach ($abs as $arr) {
            $temp = new \stdClass();
            foreach ($arr as $key => $value) {
                if($key == 'tgl') {
                    $temp->{$key} = is_null($value) ? null : date('d/m/Y', strtotime($value));
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
        return Excel::download(new AbsensiFingerExport($merged), 'absensi_finger_'.$tahun.'_'.$bulan.'_'.$pin.'.xlsx');
    }

    private function parseGolongan($raw)
    {
        if(in_array($raw, ['ALL', 'HONOR'])) {
            return $raw;
        } else if($raw == '0') {
            return ['I', 'II'];
        } else if($raw == '1') {
            return ['III', 'IV'];
        } else {
            return null;
        }
    }

}
