<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Models\TrJenisAbsen;
use App\Http\Controllers\DesktopBaseController;
use Illuminate\Database\QueryException;
use DataTables;
use DB;
use Auth;

class KodeAbsensiController extends DesktopBaseController
{
	protected $route = 'master-data.kode-absensi.';

    public function __construct() 
    {
    	parent::__construct();
    }

    public function index()
    {
        isAllow('show', 'kode-absensi');
    	$data = [
    		'route' => $this->route,
    		'breadcrumb' => [
    			'Master Data' => true,
    			'Kode Absensi' => route($this->route.'index'),
    		],
    	];
    	return $this->view($this->route . 'index', $data);
    }

    public function getTable()
    {
        isAllow('show', 'kode-absensi');
        $codes = DB::select("select * from (
            select ROW_NUMBER() OVER(ORDER BY kode asc) AS rownum, kode, nama, status from tr_jenis_absens where status <> 3
        ) a");

        return DataTables::of($codes)
            ->addColumn('status', function ($code) {
                return $code->status ? '<span class="badge badge-success">Aktif</label>' : '<span class="badge badge-danger">Tidak Aktif</label>';
            })
            ->addColumn('action', function ($code) {
                $temp = '';
                if(allow('edit', 'kode-absensi')) {
                    $temp .= ' <a href="'.route($this->route . 'edit', encrypt($code->kode)).'" class="btn btn-secondary btn-sm" title="Edit Kode Absen"><i class="fa fa-edit"></i></a> ';
                } else {
                    $temp .= btn_disabled('edit');
                }
                if(allow('delete', 'kode-absensi')) {
                    $temp .= '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($code->kode)).'\')" title="Hapus Kode Absen"><i class="fa fa-trash"></i></a>';
                } else {
                    $temp .= btn_disabled('trash');
                }
                return $temp;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        isAllow('create', 'kode-absensi');
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Master Data' => true,
                'Kode Absensi' => route($this->route.'index'),
                'Tambah Kode' => route($this->route.'create'),
            ],
        ];
        return $this->view($this->route . 'create', $data);
    }

    public function store(Request $request)
    {
        isAllow('store', 'kode-absensi');
        $request->validate([
            'kode' => 'required|max:4|unique:tr_jenis_absens,kode',
            'nama' => 'required|max:100',
            'status' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();
            //save the data to db
            TrJenisAbsen::insert([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'status' => $request->input('status'),
                'created_by' => Auth::user()->getUser()->username,
                'created_at' => date('Y-m-d H:i:s'),
            ]);    
            DB::commit();

            $this->writeLog(__METHOD__ . ' >>> Berhasil menyimpan data kode absensi baru');
            return redirect()->route($this->route . 'index')->with('swalSuccess', 'Berhasil menyimpan data');   
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan saat menyimpan data, hubungi administrator');   
        }

    }

    public function edit($kode)
    {
        isAllow('edit', 'kode-absensi');
        $kode = decrypt($kode);
        $row = TrJenisAbsen::select('kode', 'nama', 'status')->where('kode', $kode)->first();
        
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Master Data' => true,
                'Kode Absensi' => route($this->route.'index' ),
                'Edit Data Kode' => route($this->route.'create'),
            ],
            'row' => $row,
        ];
        return $this->view($this->route . 'edit', $data);
    }

    public function update(Request $request, $kode)
    {
        isAllow('update', 'kode-absensi');
        $kode = decrypt($kode);
        $request->validate([
            'nama' => 'required|max:100',
            'status' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();
            //save the data to db
            TrJenisAbsen::where('kode', $kode)->update([
                'nama' => $request->input('nama'),
                'status' => $request->input('status'),
                'updated_by' => Auth::user()->getUser()->username,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);    
            DB::commit();

            $this->writeLog(__METHOD__ . ' >>> Berhasil menyimpan perubahan data kode absensi baru');
            return redirect()->route($this->route . 'index')->with('swalSuccess', 'Berhasil menyimpan perubahan');   
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan saat menyimpan perubahan, hubungi administrator');   
        }

    }

    public function destroy($id)
    {
        isAllow('delete', 'kode-absensi');
        $id = decrypt($id);
        TrJenisAbsen::where('kode', $id)->update([
            'status' => 3, 
            'updated_by' => Auth::user()->getUser()->username, 
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return json_encode(1);
    }
}
