<?php

namespace App\Http\Controllers\TransaksiData;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Validator;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use App\Models\TrJenisAbsen;
use App\Models\TpFormulirHeader;
use App\Models\TpFormulirDetail;
use App\Models\TpPeserta;
use App\Models\TrKuotaCutiTahunan;
use App\Models\TrKuotaCutiPanjang;
use App\Http\Controllers\Api\SimkarController;
use App\Jobs\RegenerateDetailAbsensiHarianIndividuJob;
use App\Jobs\MonitoringCutiTahunanIndividuJob;
use DB;
use Auth;
use Log;
use DataTables;
use Vinkla\Hashids\Facades\Hashids;

class TransaksiAbsensiController extends DesktopBaseController
{
    protected $agent;
    protected $route = 'transaksi-data.transaksi-absensi.';
    private $karyawan;

    public function __construct()
    {
        parent::__construct();
        $this->agent = new Agent();
        $this->karyawan = new SimkarController;
    }

    public function index()
    {
        isAllow('show', 'transaksi-absensi');
        // dd(Auth::user());
        $unit = UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc');
        $bagian = TmBagian::where('isactive', 'Y');
                if (in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN'])) {
            $unit->where('kodeunitbagian', Auth::user()->getUser()->kode_unit);
            if (in_array(Auth::user()->getUser()->kode_unit,['01','02','03','04','05'])) {
                $bagian->where('kelompok', 'GUU')->where('isActive','Y');
            }
            else {
                $bagian->where('kelompok', 'unit usaha')->where('isActive','Y');
            }
        }

        if (Auth::user()->getUser()->jenis_user == 'USER_BAGIAN') {
            $bagian->where('kodebagian', Auth::user()->getUser()->kode_bagian);
        }

        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Transaksi Data' => true,
                'Daftar Formulir Absensi' => route($this->route.'index'),
            ],
            'units' => $unit->get(),
            'bagians' => $bagian->get(),
            'gols' => listGroupGolongan(),
        ];
        return $this->view($this->route . 'index', $data);
    }

    public function getDetail(Request $request)
    {
        $realID = Hashids::decode($request->input('rowNum'))[0];
        if($request) {
            try{
                $detail = DB::select("exec sp_get_formulir_detail_by_id ?",[$realID]);
            }
            catch (QueryException $e) {
                Log::error(__METHOD__ . " >>> " . $e->getMessage());
                $detail = [];
            }
        }
        // return response()->json('1');
        if(count($detail) > 0) {
            return response()->json($detail[0]);
        }
        return response()->json([]);
    }

    public function getTable(Request $request)
    {
        isAllow('show', 'transaksi-absensi');
        
        $bulantahun = explode(" ", $request->input("bulantahun"));
        $tahun = $bulantahun[1];
        $bulan = toNumberOfMonth($bulantahun[0]);
        $where = '';

        $user = Auth::user()->getUser();

        $sub = DB::table('tp_formulir_headers as h')
        ->select('h.id', 'h.nik_simkar', 'j.nama as nama_jenis_absen', 'h.tgl_mulai', 'h.tgl_selesai', 'h.jml_hk', 'h.status')
        ->leftJoin('tr_jenis_absens as j', 'j.kode', '=', 'h.kode_jenis_absen');

        $_qr = DB::table( DB::raw("({$sub->toSql()}) as h"))
        ->mergeBindings($sub)
        ->select('h.id', 'h.nik_simkar', 'k.nama', 'k.namaunitbagian', 'k.namabagian'
        , 'h.nama_jenis_absen', 'h.tgl_mulai', 'h.tgl_selesai', 'h.jml_hk', 'h.status')
        ->join('v_history_karyawan as k', function ($join) use($tahun, $bulan) {
            $join->on('k.nik', '=', 'h.nik_simkar')
            ->on('k.tahun', '=', DB::raw($tahun))
            ->on('k.bulan', '=', DB::raw($bulan));
        })
        ->whereRaw("MONTH(h.tgl_mulai) = {$bulan}") 
        ->whereRaw("YEAR(h.tgl_mulai) = {$tahun}");

        if ($request->input('unit') !== null && $request->input('unit') !== '' && $user->jenis_user !=='USER_BAGIAN' && $user->jenis_user !=='USER_UNIT') {
            $_qr->where('k.unit', $request->input('unit'));
        }

        if ($request->input('unit') !== null && $request->input('unit') !== '' && $request->input('bagian') !== null && $request->input('bagian') !== '' && $user->jenis_user !=='USER_BAGIAN' && $user->jenis_user !=='USER_UNIT') {
            $_qr->where('k.unit', $request->input('unit'))
            ->where('k.kodebagian', $request->input('bagian'));
        }
        
        if($user->jenis_user =='USER_BAGIAN') {
            $_qr->where('k.unit', $user->kode_unit)
            ->where('k.kodebagian', $user->kode_bagian);

        }else if($user->jenis_user =='USER_UNIT') {
            $_qr->where('k.unit', $user->kode_unit);
        }

        if ($request->input('golongan') !== null && $request->input('golongan') !== "") {
            if($request->input('golongan') == 'HONOR') {
                $_qr->whereRaw("left(k.nik,1) in ('H', 'P')");
            } else if($request->input('golongan') == 'SATPAM') {
                $_qr->whereRaw("(k.namajabatan in ('DANTON', 'PAPAM', 'SATPAM') OR k.namajabatan LIKE '%SATPAM%')");
            } else if($request->input('golongan') == 'OUTSOURCING') {
                 $_qr->whereRaw("left(k.nik,1) in ('O')");
            } 
            else {
                $_qr->where("k.karpim", $request->input('golongan'))
                ->whereRaw("left(k.nik,1) not in ('H', 'P')");
            }
        }

        return DataTables::of($_qr)
        ->addColumn('tgl_mulai', function ($fAbsensi) {
            return date('d M Y', strtotime($fAbsensi->tgl_mulai));
        })
        ->addColumn('tgl_selesai', function ($fAbsensi) {
            return date('d M Y', strtotime($fAbsensi->tgl_selesai));
        })
        ->addColumn('action', function ($fAbsensi) {
            $temp = '<a href="javascript:;"class="btn btn-info btn-sm" onclick="showDetail(\''.Hashids::encode($fAbsensi->id).'\')" title="Detail Pengajuan Absensi"><i class="fa fa-eye"></i></a>&nbsp;';

            if (is_user_menggunakan_mesin($fAbsensi->nik_simkar)) { 
                if(is_pengajuan_dlm_periode($fAbsensi->tgl_mulai, $fAbsensi->tgl_selesai)) {
                    $temp .= '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($fAbsensi->id)).'\', this, true)" title="Hapus Pengajuan Absensi"><i class="fa fa-trash"></i></a>';
                } else {
                    $temp .= btn_disabled('trash');                    
                }
            } else {
                if(is_pengajuan_dlm_periode_nonmesin($fAbsensi->tgl_mulai, $fAbsensi->tgl_selesai)) {
                $temp .= '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($fAbsensi->id)).'\', this, true)" title="Hapus Pengajuan Absensi"><i class="fa fa-trash"></i></a>';
                } else {
                    $temp .= btn_disabled('trash');                    
                }
            }

            return $temp;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function destroy($id)
    {
        $id_formulir_header = decrypt($id);
        $formulir = TpFormulirHeader::find($id_formulir_header);
        try {
            DB::statement("exec sp_delete_formulir_absensi_by_id ?,?,?", [
                $id_formulir_header,
                Auth::user()->getUser()->username,
                "Menghapus formulir absensi dengan nik_simkar={$formulir->nik_simkar}, kode={$formulir->kode_jenis_absen}, tgl={$formulir->tgl_mulai} s/d {$formulir->tgl_selesai}, alasan={$formulir->alasan}"
            ]);
            // $this->writeLog('Berhasil menghapus data formulir pengajuan absensi NIK:'.$formulir->nik_simkar.' dari database');
            RegenerateDetailAbsensiHarianIndividuJob::dispatch($formulir->nik_simkar, $formulir->tgl_mulai, $formulir->tgl_selesai, Auth::user()->getUser()->username);
            MonitoringCutiTahunanIndividuJob::dispatch($formulir->nik_simkar);
            return json_encode(1);
        } catch (QueryException $e) {
            Log::error("Gagal menghapus formulir absensi.");
            Log::error($e->getMessage());
            return json_encode(0);
        }
    }
}
