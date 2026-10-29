<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\DesktopBaseController;
use App\Models\TrLiburCutiBersama;
use Validator;
use DataTables;
use DB;
use Auth;

class LiburCutiBersamaController extends DesktopBaseController
{
    protected $route = 'master-data.libur-cuti-bersama.';

    public function __construct() 
    {
    	parent::__construct();
    }

    public function index()
    {
        isAllow('show', 'libur-cuti-bersama');
        $data = [
          'route' => $this->route,
          'breadcrumb' => [
             'Master Data' => true,
             'Libur & Cuti Bersama' => route($this->route.'index'),
         ],
     ];
     return $this->view($this->route . 'index', $data);
 }

 public function getTable()
 {
    isAllow('show', 'libur-cuti-bersama');

    $liburs = DB::select("select * from (
        select ROW_NUMBER() OVER(ORDER BY tgl desc) AS rownum, id, tgl, uraian, status, 
        CASE is_cb  WHEN '0' THEN 'Libur Nasional'  ELSE 'Cuti Bersama' END as jenis_libur
        from tr_libur_cuti_bersamas
    ) a");

    return DataTables::of($liburs)
    ->addColumn('status', function ($libur) {
        return $libur->status ? '<span class="badge badge-success">Aktif</label>' : '<span class="badge badge-danger">Tidak Aktif</label>';
    })
    ->addColumn('action', function ($libur) {
        $temp = '';
        if(allow('edit', 'libur-cuti-bersama')) {
            $temp .= ' <a href="'.route($this->route . 'edit', encrypt($libur->id)).'" class="btn btn-secondary btn-sm" title="Edit Libur/Cuti Bersama"><i class="fa fa-edit"></i></a> ';
        } else {
            $temp .= btn_disabled('edit');
        }

        if(allow('delete', 'libur-cuti-bersama')) {
            $temp .= '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($libur->id)).'\')" title="Hapus Libur/Cuti Bersama"><i class="fa fa-trash"></i></a>';
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
    isAllow('create', 'libur-cuti-bersama');
    $data = [
      'route' => $this->route,
      'breadcrumb' => [
         'Master Data' => true,
         'Libur & Cuti Bersama' => route($this->route.'index'),
         'Tambah Jadwal Libur & Cuti Bersama' => route($this->route.'create'),
     ],
 ];
 return $this->view($this->route . 'create', $data);
}

public function store(Request $request)
{
    isAllow('create', 'libur-cuti-bersama');
    
    $validator = Validator::make($request->all(), [
        'jenis'     => 'required|boolean',
        'tanggal'   => 'required|date_format:d-m-Y',
        'uraian'    => 'required|max:200',
        'status'    => 'required|boolean',
    ]);

    $validator->after(function ($validator) use ($request) {
        if(TrLiburCutiBersama::whereTgl(date('Y-m-d', strtotime($request->tanggal)))->count() > 0) {
            $validator->errors()->add('tanggal', 'tanggal telah digunakan.');
        }
    });

    if ($validator->fails()) {
        return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }
        // dd($request->all());

    try {
        DB::beginTransaction();
            //save the data to db
        TrLiburCutiBersama::insert([
            'tgl'           => date('Y-m-d', strtotime($request->input('tanggal'))),
            'uraian'        => $request->input('uraian'),
            'is_cb'         => $request->input('jenis'),
            'status'        => $request->input('status'),
            'created_by'    => Auth::user()->getUser()->username,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);    
        DB::commit();

        $this->writeLog(__METHOD__ . ' >>> Berhasil menyimpan jadwal libur dan cuti bersama');
        return redirect()->route($this->route . 'index')->with('swalSuccess','Berhasil menyimpan data');   
    } catch (QueryException $e) {
        DB::rollBack();
        return redirect()->back()->withError('Terjadi kesalahan saat menyimpan data, hubungi administrator');   
    }

}

public function edit($id)
{
    isAllow('edit', 'libur-cuti-bersama');
    $id = decrypt($id);
    // $row = DB::select("SELECT id, CONVERT(VARCHAR(20), tgl,105) AS tgl, uraian, status,is_cb FROM tr_libur_cuti_bersamas WHERE id =1");
    $row = TrLiburCutiBersama::select('id', DB::raw("CONVERT(VARCHAR(20), tgl,105) AS tgl"), 'uraian', 'status', 'is_cb')
            ->whereId($id)->first();
    $data = [
        'route' => $this->route,
        'breadcrumb' => [
            'Master Data' => true,
            'Libur & Cuti Bersama' => route($this->route.'index'),
            'Edit Jadwal' => route($this->route.'create'),
        ],
        'row' => $row,
    ];
    return $this->view($this->route . 'edit', $data);
}

public function update(Request $request, $id)
{
    isAllow('edit', 'libur-cuti-bersama');
    $id = decrypt($id);

    $validator = Validator::make($request->all(), [
        'jenis'     => 'required|boolean',
        'tanggal'   => 'required|date_format:d-m-Y',
        'uraian'    => 'required|max:200',
        'status'    => 'required|boolean',
    ]);

    $validator->after(function ($validator) use ($request, $id) {
        if(TrLiburCutiBersama::whereTgl(date('Y-m-d', strtotime($request->tanggal)))
            ->where('id','<>',$id)
            ->count() > 0) {
            $validator->errors()->add('tanggal', 'tanggal telah digunakan.');
    }
});

    if ($validator->fails()) {
        return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    try {
        DB::beginTransaction();
            //save the data to db
        TrLiburCutiBersama::where('id', $id)->update([
            'tgl'           => date('Y-m-d', strtotime($request->input('tanggal'))),
            'uraian'        => $request->input('uraian'),
            'is_cb'         => $request->input('jenis'),
            'status'        => $request->input('status'),
            'updated_by'    => Auth::user()->getUser()->username,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        DB::commit();

        $this->writeLog(__METHOD__ . ' >>> Berhasil menyimpan perubahan jadwal libur & cuti bersama');
        return redirect()->route($this->route . 'index')->with('swalSuccess','Berhasil menyimpan data');    
    } catch (QueryException $e) {
        DB::rollBack();
        return redirect()->back()->withError('Terjadi kesalahan saat menyimpan perubahan, hubungi administrator');   
    }

}

public function destroy($id)
{
    isAllow('delete', 'libur-cuti-bersama');
    $id = decrypt($id);
    try {
        TrLiburCutiBersama::where('id', $id)->delete();
        return json_encode(1);
    } catch (Exception $e) {
        return json_encode(0);            
    }
}
}
