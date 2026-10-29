<?php

namespace App\Http\Controllers\Admin\Normalisasi;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use Illuminate\Database\QueryException;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use App\Models\TpInsentifNormalisasi;
use Jenssegers\Agent\Agent;
use DataTables;
use Validator;
use DB;
use Auth;
use Log;
use App\Http\Controllers\Api\SimkarController;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NormalisasiInsentifImport;
use Illuminate\Support\Facades\Storage;

class InsentifController extends DesktopBaseController
{
    protected $_route = 'admin.normalisasi.insentif.';

    public function __construct()
    {
    	parent::__construct();
        $this->agent = new Agent();
        $this->karyawan = new SimkarController;
    }

    public function index()
    {
    	$data = [
        'route' => $this->_route,
        'breadcrumb' => [
          'Admin' => true,
          'Normalisasi' => true,
          'Insentif' => true
        ], 
        'periods' => TpInsentifNormalisasi::select('periode')->distinct()->get()->pluck('periode')
      ];
    	return $this->view($this->_route . 'index', $data);
    }

    public function getTable()
    {
        $datum = TpInsentifNormalisasi::select('id', 'nik', 'periode', 'keterangan')->with(['karyawan', 'nonptpn4'])->get();

        return DataTables::of($datum)
            ->addColumn('nama', function ($data) {
            	if(!is_null($data->karyawan)) {
            		return $data->karyawan->nama;
            	} else if (!is_null($data->nonptpn4)) {
            		return $data->nonptpn4->nama_karyawan;
            	} else {
            		return '';
            	}
            })
            ->addColumn('action', function ($data) {
                return '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->_route . 'destroy', encrypt($data->id)).'\')" title="Hapus Kode Absen"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function uploads()
    {
        $data = [
            'route' => $this->_route,
            'breadcrumb' => [
                'Admin' => '#',
                'Normalisasi' => '#',
                'Insentif' => route($this->_route . 'index'),
                'Uploads' => '#',
            ],
            'periods' => TpInsentifNormalisasi::select('periode')->distinct()->get()->pluck('periode'),
        ];
        return $this->view($this->_route . 'uploads', $data);
    }

    public function deleteUpload($periode)
    {
        return response()->json(TpInsentifNormalisasi::where('periode', $periode)->delete() > 0 ? 1 : 0);
    }

    public function create()
    {
        $data = [
            'route' => $this->_route,
            'breadcrumb' => [
                'Admin' => '#',
                'Normalisasi' => '#',
                'Insentif' => route($this->_route . 'index'),
                'Create' => '#',
            ]
        ];
        return $this->view($this->_route . 'create', $data);
    }

    public function store(Request $request)
    {
        $request->request->add(['periode' => $request->tahun . (toNumberOfMonth($request->bulan) < 10 ? '0'.toNumberOfMonth($request->bulan) : toNumberOfMonth($request->bulan))]);
        $request->validate([
            'tahun' => 'required',
            'bulan' => 'required',
            'file' => 'required|file|mimes:xlsx',
            'periode' => 'unique:tp_insentif_normalisasi,periode'
        ]);
        $path = $request->file('file')->storeAs('normalisasi/insentif', time() .'_'. $request->file('file')->getClientOriginalName());        
        Excel::import(new NormalisasiInsentifImport($request->tahun, toNumberOfMonth($request->bulan)), $path);
        return redirect()->route($this->_route . 'index');
    }

    public function destroy($enctId)
    {
        $id = decrypt($enctId);
        return response()->json(TpInsentifNormalisasi::find($id)->delete());
    }
}
