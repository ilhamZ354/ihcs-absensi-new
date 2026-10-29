<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use App\Models\Simkar\TmJabatan;
use App\Models\Simkar\KaryawanPtpn4;
use App\Models\Simkar\KaryawanNonPtpn4;
use DB;

class SimkarController extends DesktopBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listUnit(Request $request)
    {
        $units = UnitBagianMaster::whereStatus(1)
        ->orderBy('namaunitbagian', 'asc')
        ->get();

        return $this->toJson($units);
    }

    public function listBagian(Request $request)
    {
        $bagians = TmBagian::where('isactive', 'Y');

        if($request->has('unit'))
            $bagians->whereExists(function($query) use ($request) {
                $query
                ->select('*')->from('unitbagianmaster as u')
                ->whereRaw('u.kelompok = '.TmBagian::getTableName().'.kelompok')
                ->where('u.kodeunitbagian', $request->input('unit'));
            });

        $bagians->orderBy('namabagian', 'asc');

        return $this->toJson($bagians->get());
    }

    public function listJabatan(Request $request)
    {
        $jabs = TmJabatan::select('kodejabatan', 'jabatan', 'kelompok', 'jenis_karyawan', 'isactive')
        ->where('isactive', 'Y');

        if($request->has('jenis_karyawan'))
            $jabs->where('jenis_karyawan', $request->input('jenis_karyawan'));

        if($request->has('unit'))
            $jabs->whereExists(function($query) use ($request) {
                $query
                ->select('*')->from('unitbagianmaster as u')
                ->whereRaw('u.kelompok = tm_jabatan.kelompok')
                ->where('u.kodeunitbagian', $request->input('unit'));
            });

        $jabs->orderBy('jabatan', 'asc');
        return $this->toJson($jabs->get());
    }
    
    public function takeKaryawan($nik)
    {
      return $emps = DB::table('v_karyawan_all as k')
      ->select('k.nik', 'k.nik_sap', 'k.nama','k.tanggal_bekerja')
      // ->where('k.status','01')
      ->whereIn('k.status',['01','02','03'])
      ->where('k.nik',$nik)->first();

    }

    public function takeRekanPengganti(Request $request)
    {
        sleep(1);
        $emps = DB::table('v_karyawan_all')
        ->select('nik', 'nik_sap', 'nama', 'namaunit', 'namabagian')
        ->where('nama','like','%'.$request->input('query').'%');
        return $this->toJson($emps->get());
    }

    public function takeListRS(Request $request)
    {
        //mengambil list Rumah Sakit di Medan
        $file = file_get_contents(resource_path('assets/lookups/rumah_sakit.json'));
        return json_decode(json_decode(json_encode($file, true))); 
    }

    public function listKaryawan(Request $request)
    {
        $nonptpn4 =['HONOR','MUSIMAN','OUTSOURCING'];

        if($request->has('golongan') && !is_null($request->input('golongan')))
        {
            if (in_array($request->input('golongan'), $nonptpn4)) {
                $emps = DB::table('tm_karyawan_nonptpn4 as k')
                ->select('k.nik', 'k.nik_sap', 'k.nama_karyawan as nama','k.tgl_bekerja as tanggal_bekerja', 'k.kodebagian', 'j.jabatan','u.namaunitbagian as unit','b.namabagian as bagian')
                ->join('unitbagianmaster as u','k.unit','=','u.kodeunitbagian')
                ->join('tm_bagian as b','k.kodebagian','=','b.kodebagian')
                ->join('tm_jabatan as j','k.kodejabatan','=','j.kodejabatan')
                ->where('k.status','01')
                ->where('k.tipe_karyawan', $request->input('golongan'));
            }
            else {
                $emps = DB::table('karyawan_ptpn4 as k')
                ->select('k.nik', 'k.nik_sap', 'k.nama','k.tanggal_bekerja', 'u.namaunitbagian as unit', 'b.namabagian as bagian', 'k.kodejabatan','j.jabatan','u.namaunitbagian','b.namabagian')
                ->join('unitbagianmaster as u','k.unit','=','u.kodeunitbagian')
                ->join('tm_bagian as b','k.kodebagian','=','b.kodebagian')
                ->join('tm_jabatan as j','k.kodejabatan','=','j.kodejabatan')
                ->where('k.status','01')
                ->where('k.karpim',$request->input('golongan'));
            }
        }
        else {
            return 'Golongan harus ditentukan';
        }

        if($request->has('unit') && !empty($request->input('unit')))
            $emps->where('k.unit', $request->input('unit'));

        if($request->has('bagian') && !empty($request->input('bagian')))
            $emps->where('k.kodebagian', $request->input('bagian'));

        if($request->has('jabatan') && !empty($request->input('jabatan')))
            $emps->where('k.kodejabatan', $request->input('jabatan'));

        if($request->has('nikNama') && !empty($request->input('nikNama')))
            $emps->where(function ($query) use ($request) {
                $query->where('k.nik', 'like', '%'.$request->nikNama.'%')
                ->orWhere('k.nama', 'like', '%'.$request->nikNama.'%');
            });

        $emps->orderBy('nama', 'asc');
        return $this->toJson($emps->get());
    }

    public function listGolongan(Request $request)
    {
        $gols = DB::connection('simkar')
        ->table('tm_lookups_sdm')
        ->select('lookup_value as kode', DB::raw("'Golongan ' + lookup_value as golongan"))
        ->whereRaw("category in('MKG_KARPEL', 'MKG_KARPIM')");

        if($request->has('gol')) {
            $gols->where('lookup_value', $request->input('gol'));
        }
        return $this->toJson($gols->get());
    }

    public function listAllKaryawanSimple(Request $request)
    {
        $karyawan = DB::table('v_history_karyawan as k')
        ->select('k.nama','k.nik', 'namaunitbagian', 'namabagian', 'namajabatan')
        ->whereNotNull('k.nik')
        ->where('tahun', date('Y'))
        ->where('bulan', date('m'));
        // ->where('status', '01');

        // $ptpn4 = DB::table('karyawan_ptpn4 as k')
        //                 ->select('k.nama', 'k.nik')
        //                 ->where('status', '01');

        // $nonptpn4 = DB::table('tm_karyawan_nonptpn4 as k')
        //                 ->select('k.nama_karyawan as nama', 'k.nik')
        //                 ->where('status', '01');

        if($request->has('unit') && !empty($request->input('unit'))) {
            // $ptpn4->where('k.unit', $request->input('unit'));
            // $nonptpn4->where('k.unit', $request->input('unit'));
            
            $karyawan->where('k.unit', $request->input('unit'));
        }

        if($request->has('bagian') && !empty($request->input('bagian'))) {
            // $ptpn4->where('k.kodebagian', $request->input('bagian'));
            // $nonptpn4->where('k.kodebagian', $request->input('bagian'));
            $karyawan->where('k.kodebagian', $request->input('bagian'));
        }

        if($request->has('jabatan') && !empty($request->input('jabatan'))) {
            $karyawan->where('k.kodejabatan', $request->input('jabatan'));
            // $ptpn4->where('k.kodejabatan', $request->input('jabatan'));
            // $nonptpn4->where('k.kodejabatan', $request->input('jabatan'));
        }

        if($request->has('nikNama') && !empty($request->input('nikNama'))) {
            $karyawan->where(function ($query) use ($request) {
                $query->where('k.nik', 'like', '%'.$request->nikNama.'%')
                ->orWhere('k.nama', 'like', '%'.strtoupper($request->nikNama).'%');
            });

            // $ptpn4->where(function ($query) use ($request) {
            //     $query->where('k.nik', 'like', '%'.$request->nikNama.'%')
            //     ->orWhere('k.nama', 'like', '%'.$request->nikNama.'%');
            // });

            // $nonptpn4->where(function ($query) use ($request) {
            //     $query->where('k.nik', 'like', '%'.$request->nikNama.'%')
            //     ->orWhere('k.nama_karyawan', 'like', '%'.$request->nikNama.'%');
            // });
        }
        $karyawan->distinct('k.nik');
        // return $this->toJson(array_merge($ptpn4->get()->toArray(), $nonptpn4->get()->toArray()));
         return $this->toJson($karyawan->get()->toArray());

    }
}
