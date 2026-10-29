<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Excel\Exports\WFHExport as Export;
use Auth;
use DB;


class WfhController extends DesktopBaseController
{
  protected $route = 'laporan.wfh.';

  public function __construct()
  {
    parent::__construct();
  }

  public function index(Request $request)
  {
    if (Auth::user()->getUser()->kode_unit !== '00') {
      abort(404);
    }
    
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
      'filter' => (object) $request->all(),
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
     // array_push($bagians, '');

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

    if (Auth::user()->getUser()->jenis_user == 'USER_BAGIAN') {
       $kodebagian  = Auth::user()->getUser()->kode_bagian;
    }
    else {
      $kodebagian  = !is_null($request->bagian) ? $request->bagian : '';
    }

    $tahun = $request->tahun;
    $bulan = toNumberOfMonth($request->bulan);
    $golongan  = $request->golongan;
    $jenis_laporan  = !is_null($request->jenis_laporan) ? $request->jenis_laporan : 'ANGKA';

        // dd($tahun, $bulan, $kodebagian, $golongan, $jenis_laporan);

    $summaries = DB::select("exec sp_new_laporan_wfh ?,?,?,?,?", [        
      $tahun
      , $bulan
      , $kodebagian
      , $golongan
      , $jenis_laporan
    ]);

    $newSummaries = [];

    foreach ($summaries as $summary) {
      foreach ($summary as $key => $val) {
        if(!in_array($key, ["namabagian", "tahun", "bulan"])) {
          if(empty($val)) {
            $summary->{$key} = [null, null];
          } else {
            $summary->{$key} = explode('|', $val);
          }
        }
      }
    }
    return collect($summaries);        

  }

  public function export(Request $request)
  {
    $collect = collect($this->get_summary($request));

    if(count($collect) > 0) {
      $tahun = $request->tahun;
      $bulan = toNumberOfMonth($request->bulan);
      $kodeunitbagian  = $request->nama_unit;
      $jenis_laporan = $request->jenis_laporan;
      $kodebagian  = !is_null($request->bagian) ? "_" . $request->bagian : "";

      return Excel::download(new Export($collect, $request->tahun, $request->bulan, $jenis_laporan), "laporan_rekap_{$jenis_laporan}_WFH_vs_Kerja_{$tahun}_{$bulan}_{$kodebagian}.xlsx");
    }
    else {
      return redirect()->route('laporan.absensi.index')
      ->with('swalWarning', 'Tidak ada data yang akan dieksport ke Excel');
    }
  }
}