<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use DataTables;
use DB;

class PesertaController extends DesktopBaseController
{
	protected $route = 'master-data.peserta.';

    public function __construct()
    {
    	parent::__construct();
    }

    public function index()
    {
        isAllow('show', 'peserta');
    	$data = [
    		'route' => $this->route,
    		'breadcrumb' => [
    			'Master Data' => true,
    			'Peserta Absensi' => route($this->route.'index'),
    		],
    	];
    	return $this->view($this->route . 'index', $data);
    }

    public function getTable()
    {
        isAllow('show', 'peserta');
    	$atts = DB::select("select * from (
            select ROW_NUMBER() OVER(ORDER BY pin asc) AS rownum, pin, nik_simkar, nama_mesin, tgl_bekerja, satpam, status from tp_pesertas where (status is null or status <> 3)
        ) a");

        return DataTables::of($atts)
            ->addColumn('status', function ($att) {
                if($att->status || is_null($att->status))
                	return '<span class="badge badge-success">Aktif</label>';
                else 
                	'<span class="badge badge-danger">Tidak Aktif</label>';
            })
            ->addColumn('satpam', function ($att) {
                return $att->satpam == 1 ? '<span class="badge badge-warning">Satpam</label>' : '<span class="badge badge-primary">Bukan</label>';
            })
            ->rawColumns(['status', 'satpam', 'action'])
            ->make(true);
    }

    public function create()
    {
        isAllow('create', 'peserta');
    	sinkronisasi_peserta_mdb();
    	return redirect()->back()->with('swalSuccess', 'Sinkronisasi data peserta berhasil dilakukan');
    }
    
}
