<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use Auth;
use DataTables;
use DB;

class KehadiranHarianController extends DesktopBaseController
{
	protected $route = 'laporan.kehadiran-harian.';

    function __construct()
    {
    	parent::__construct();
    }

    public function index()
    {
    	//kalau yang login user pribadi
        //langsung isi tabel.
        //kalau yg login user bagian (punya akses ke mesin)
        //harus memilih nama karyawan dulu
    	$data = [
          'route' => $this->route,
          'breadcrumb' => [
          	'Laporan' => true, 
          	'Kehadiran Harian' => route($this->route.'index')
          ],
        ];
        return $this->view($this->route . 'index', $data);
    }

    public function getTables($tahun, $bulan)
    {
    	// return \DB::connection('att')->table('VAttTdCp')
    	// 	->where(\DB::connection('att')->raw("tgl between date('".$tahun."-".$bulan."-01') and date('".$tahun."-".$bulan."-01', '+1 month')"))
    	// 	->get();

    	$data = DB::select("exec sp_get_summary_absensi_harian ?,?,?", [
    		$tahun, $bulan, Auth::user()->getUser()->nik
    	]);
    	return $data;

    }

    public function getTable()
    {
        // isAllow('show', 'kehadiran-harian');
        $rows = \DB::select("exec sp_get_summary_absensi_harian ?,?,?", [
    		date('Y'), date('m'), Auth::user()->getUser()->nik
    	]);

        if(!property_exists($rows[0], 'tahun') && !property_exists($rows[0], 'bulan')) {
            $rows = [];
        }

        $datatables = DataTables::of($rows)
        ->addColumn('nama', function ($row) {
        	return Auth::user()->getUser()->nama;
        });

        $rawColumns = [];
        for($i=1; $i<=31; $i++) {

        	$datatables->addColumn($i, function ($row)  use ($i){
        		if(in_array(Auth::user()->getUser()->kode_unit, ['00', '62'])){
        			$libur = ['Saturday', 'Sunday'];
        		} else {
        			$libur = ['Sunday'];
        		}
        		
        		if(in_array(date('l', strtotime($row->tahun .'-'.$row->bulan.'-'.$i)), $libur) || is_libur_cuti_bersama($row->tahun .'-'.$row->bulan.'-'.$i)) {
        			if($row->{$i} == 'CB') {
        				return '<div style="background-color: #f5bbbb; ">'.$row->{$i} .'</div>';
        			} elseif($row->{$i} == 'K') {
        				return '<div style="">'. $row->{$i} .'</div>';
        			} else {
        				return '<div style="background-color: #f06f6f; color: #fff;">'. $row->{$i} .'</div>';
        			}
        		} else {
        			if($row->{$i} == 'N/A') {
        				return '<div style="background-color: #ffbc65;">'. $row->{$i} .'</div>';
        			} else {
        				return '<div style="">'. $row->{$i} .'</div>';
        			}
        		}
        	});

            array_push($rawColumns, 0);
        	array_push($rawColumns, $i);
        }
    	$datatables->rawColumns($rawColumns);
        return $datatables->make(true);
    }
    
}
