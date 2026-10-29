<?php

namespace App\Http\Controllers\Admin\Normalisasi;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use Illuminate\Database\QueryException;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use Jenssegers\Agent\Agent;
use Validator;
use DB;
use Auth;
use Log;
use App\Http\Controllers\Api\SimkarController;

class AbsensiController extends DesktopBaseController
{
    protected $route = 'admin.normalisasi.absensi.';

    public function __construct()
    {
    	parent::__construct();
        $this->agent = new Agent();
        $this->karyawan = new SimkarController;
    }

    public function index()
    {
    	allow('show', 'normalisasi-absensi');
        $unit = UnitBagianMaster::where('status', 1);
        $bagian = TmBagian::where('isactive', 'Y');

        if (in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN'])) {
            $unit->where('kodeunitbagian', Auth::user()->getUser()->kode_unit);
            if (in_array(Auth::user()->getUser()->kode_unit,['01','02','03','04','05'])) {
                $bagian->where('kelompok', 'GUU')->where('isActive','Y');
            }
            else {
                $bagian->where('kelompok', 'unit usaha')->where('isActive','Y');
            }
            //langsung cek keberadaan datanya
            $is_uploaded = DB::table('tr_file_upload_ctcps')
            ->where('kode_unit', Auth::user()->getUser()->kode_unit)
            ->where('verified_status',1)
            ->get();

            if (count($is_uploaded) <= 0) {
                return redirect()->route('home')
                ->with('swalWarningLink', 'Unit/Bagian anda belum mengupload Template Cuti Tahunan/Cuti Panjang')
                // ->with('warning_footer','<a href="'.route("master-data.ctcp.index-download").'">Download Template</a>');
                ->with('warning_footer','Mohon maaf, Periode upload/download Cuti Tahunan/Panjang sudah berakhir.');
            }
        }

        if (Auth::user()->getUser()->jenis_user == 'USER_BAGIAN') {
            $bagian->where('kodebagian', Auth::user()->getUser()->kode_bagian);
            //langsung cek keberadaan datanya
            $is_uploaded = DB::table('tr_file_upload_ctcps')
            ->where('kode_unit', Auth::user()->getUser()->kode_unit)
            ->where('kode_bagian',  Auth::user()->getUser()->kode_bagian)
            ->get();

            if (count($is_uploaded) <= 0) {
                return redirect()->route('home')
                ->with('swalWarningLink', 'Unit/Bagian anda belum mengupload Template Cuti Tahunan/Cuti Panjang')
                // ->with('warning_footer','<a href="'.route("master-data.ctcp.index-download").'">Download Template</a>');
                ->with('warning_footer','Mohon maaf, Periode upload/download Cuti Tahunan/Panjang sudah berakhir.');
            }
        }
        $unit->orderBy('kodeunitbagian', 'asc');
        $bagian->orderBy('namabagian', 'asc');

        $data = [
        'route' => $this->route,
        'breadcrumb' => [
          'Normalisasi Data' => true,
          'Normalisasi Absensi' => route($this->route.'index'),
        ],
        'units' => $unit->get(),
        'bagians' => $bagian->get(),
        'gols' => listGroupGolongan(),
      ];
        if (!$this->agent->isMobile()) {
            return $this->view($this->route . 'index', $data);
        } else {
            // return $this->view($this->route . 'index', $data);
            // return view('mobile.home');
            return $this->view($this->route . 'index', $data);
            // return('Silahkan akses aplikasi dengan perangkat komputer desktop/laptop');
        }
    }

 public function getTable(Request $request)
    {
        if (in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN'])) {
            $request->request->add(['unit' => Auth::user()->getUser()->kode_unit]);
        }

        if (Auth::user()->getUser()->jenis_user == 'USER_BAGIAN') {
            $request->request->add(['bagian' => Auth::user()->getUser()->kode_bagian]);
        }

        $emps = DB::table('v_karyawan_all as k');

        if ($request->has('golongan') && !is_null($request->input('golongan'))) {
            if($request->input('golongan') == 'HONOR')
                $emps= $emps->whereRaw("left(k.nik, 1) in ('P', 'H')");
            else
                $emps= $emps->where('k.tipe_karyawan', $request->input('golongan'));
        }

        if ($request->has('unit') && !empty($request->input('unit'))) {
            $emps= $emps->where('k.unit', $request->input('unit'));
        }

        if ($request->has('bagian') && !empty($request->input('bagian'))) {
            $emps= $emps->where('k.bagian', $request->input('bagian'));
        }

        // if ($request->has('jabatan') && !empty($request->input('jabatan'))) {
        //     $emps= $emps->where('k.jabatan', $request->input('jabatan'));
        // }

        if ($request->has('nikNama') && !empty($request->input('nikNama'))) {
            $emps= $emps->where('k.nama', 'like', '%'.$request->nikNama.'%');
        }

        $emps= $emps->orderBy('nama', 'asc')->get();

        if (count($emps) > 0) {
            return $this->toJson($emps);
        } else {
            return response()->json('empty');
        }
    }

    public function process(Request $request) {
        $request->validate([
            'kodeunitbagian' => 'required',
            'kodebagian' => 'required',
            'golongan' => 'required',
            'tgl_mulai' => 'required|date_format:d-m-Y',
            'tgl_selesai' => 'required|date_format:d-m-Y',
            'alasan' => 'required',
            'pesertas' => 'required',
        ]);

        $peserta = "select * from (";
        foreach($request->pesertas as $i => $o) {
            if($i == 0)
                $peserta .= "select '{$o}' as nik";
            else
                $peserta .= " union select '{$o}' as nik";
        }
        $peserta .= ") a";

        try {
            DB::statement("execute sp_new_normalisasi_absensi ?,?,?,?,?,?,?", [
                date('Y-m-d', strtotime($request->tgl_mulai))
                , date('Y-m-d', strtotime($request->tgl_selesai))
                , $request->kodeunitbagian
                , $request->kodebagian
                , $peserta
                , $request->alasan
                , Auth::user()->getUser()->nama
            ]);
            Log::error(Auth::user()->getUser()->nama . " berhasil menjalankan normalisasi dengan data " . json_encode($request->all()));
            $this->writeLog(Auth::user()->getUser()->nama . " berhasil menjalankan normalisasi dengan data " . json_encode($request->all()));
            return json_encode([
                'status' => 1,
                'message' => 'Normalisasi absensi berhasil dilakukan'
            ]);
        } catch (Exception $e) {
            Log::error("Normalisasi absensi gagal oleh " . Auth::user()->getUser()->nama);
            Log::error($e->getMessage());
            return json_encode([
                'status' => 0,
                'message' => 'Gagal menjalankan normalisasi absensi, hubungi admin.'
            ]);
        }
    }
}
