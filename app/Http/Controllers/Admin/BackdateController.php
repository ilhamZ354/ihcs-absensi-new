<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use Illuminate\Database\QueryException;
use App\Models\TrBukaFormulirBackdate;
use  App\Models\Simkar\UnitBagianMaster;
use  App\Models\Simkar\TmBagian;
use Validator;
use DB;
use Auth;
use Log;
use DataTables;
use Vinkla\Hashids\Facades\Hashids;

class BackdateController extends DesktopBaseController
{
    protected $route = 'admin.backdate.';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Setup' => true,
                'Akses Backdate' => route($this->route.'index'),
            ]
        ];
        return $this->view($this->route . 'index', $data);
    }

    public function getTable(Request $request)
    {
        $query = DB::table('tr_buka_formulir_backdates as backdate')
        ->select(DB::raw('backdate.*'), 'unit.namaunitbagian', 'bagian.namabagian')
        ->join('unitbagianmaster as unit', 'unit.kodeunitbagian', 'backdate.kodeunitbagian')
        ->leftjoin('tm_bagian as bagian', 'bagian.kodebagian', 'backdate.kodebagian');

        return DataTables::of($query)
        ->addColumn('nama', function ($backdate) {
            return $backdate->namaunitbagian . (!is_null($backdate->namabagian) ? ' - ' . $backdate->namabagian : '');
        })
        ->editColumn('dibuka_dari', function ($backdate) {
            return date('Y-m-d H:i:s', strtotime($backdate->dibuka_dari));
        })
        ->editColumn('dibuka_sampai', function ($backdate) {
            return date('Y-m-d H:i:s', strtotime($backdate->dibuka_sampai));
        })
        ->addColumn('status', function ($backdate) {
            if ($backdate->status == 0)
                return '<span class="badge badge-danger">Tidak Aktif</span>';
            else 
                return '<span class="badge badge-success">Aktif</span>';
        })
        ->addColumn('action', function ($backdate) {
            if ($backdate->status == 1)
                return'<a href="javascript:;" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Nonaktifkan"  onclick="deactivate(event, \''.route($this->route . 'deactivate', encrypt($backdate->id)).'\')"><i class="fa fa-ban"></i></a>';
            else
                return'<button type="button" class="btn btn-sm btn-default" disabled="" data-toggle="tooltip" title="Nonaktifkan"><i class="fa fa-ban"></i></button>';
        })
        ->rawColumns(['status','action'])
        ->make(true);
    }

    public function create()
    {
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Setup' => true,
                'Akses Backdate' => route($this->route.'index'),
                'Create' => true,
            ],
            'units' => UnitBagianMaster::select('kodeunitbagian', 'namaunitbagian')
            ->where('status', 1)
            ->orderBy('kodeunitbagian')
            ->get(),
            'bagians' => TmBagian::select('kodebagian', 'namabagian')
            ->where('isactive', 'Y')
            ->where('kelompok', 'KANPUS')
            // ->whereNotIn('flag',['DIREKTUR'])
            ->orderBy('namabagian')
            ->get()
        ];
        return $this->view($this->route . 'create', $data);
    }

    public function store(Request $request)
    {
        $messages = [
            'bagian.required_if' => 'Isian :attribute harus berisi jika memilih unit kerja KANTOR DIREKSI.',
            'after_or_equal' => 'Nilai isian :attribute minimal harus sama atau lebih besar dari nilai :date.',
        ];

        $validator = Validator::make($request->all(), [
            'unit_kerja' => 'required|string|max:5',
            'bagian' => 'string|max:15|required_if:unit_kerja,00',
            'tgl_ubah_mulai' => 'required|date|date_format:Y-m-d',
            'tgl_ubah_selesai' => 'required|date|date_format:Y-m-d|after_or_equal:tgl_ubah_mulai',
            'dibuka_dari' => 'required|date|date_format:Y-m-d H:i',
            'dibuka_sampai' => 'required|date|date_format:Y-m-d H:i|after_or_equal:dibuka_dari',
            'status' => 'required|boolean',
            'keterangan' => 'required|string|max:255',
        ], $messages);

        $validator->after(function ($validator) use($request) {
            if (!$this->_isDateLastMonth($request)) {
                $validator->errors()->add('tgl_ubah_mulai', 'Tanggal ubah mulai hanya boleh diisi dengan tanggal bulan lalu. !');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $backdate = [
            'kodeunitbagian' => $request->unit_kerja,         
            'tgl_ubah_mulai' => $request->tgl_ubah_mulai,
            'tgl_ubah_selesai' => $request->tgl_ubah_selesai,
            'dibuka_dari' => $request->dibuka_dari,
            'dibuka_sampai' => $request->dibuka_sampai,
            'created_by' => Auth::user()->getUser()->nik,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ];

        if($request->has('bagian'))
            $backdate['kodebagian'] = $request->bagian;

        TrBukaFormulirBackdate::create($backdate);        
        $this->writeLog('Menambah Akses backdate untuk unit:'.$request->unit_kerja . ($request->has('bagian') ? ', kodebagian:'.$request->bagian : ''));
        return redirect()->route($this->route . 'index')->with('swalSuccess', 'Backdate berhasil disimpan');
    }

    private function _isDateLastMonth($request)
    {
        $backdateCheck = collect(DB::select("select dbo.fn_cek_tanggal_backdate(?,?) as result", [$request->tgl_ubah_mulai, $request->tgl_ubah_selesai]))->first();
        return $backdateCheck ? $backdateCheck->result : 0;
    }

    public function deactivate($id)
    {
        $id_backdate = decrypt($id);
        return response()->json(TrBukaFormulirBackdate::find($id_backdate)->update(['status' => 0]));
    }
}
