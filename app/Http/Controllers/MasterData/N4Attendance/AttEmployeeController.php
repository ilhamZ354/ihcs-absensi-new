<?php

namespace App\Http\Controllers\MasterData\N4Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DesktopBaseController;
use Log;
use DB;
use Validator;
use Auth;
use Exception;
use App\Models\Attendance\Employee;
use App\Models\Attendance\EmployeeGroup;
use DataTables;

class AttEmployeeController extends DesktopBaseController
{
	protected $route = 'master-data.n4attendance.peserta.';

	public function __construct()
	{
		parent::__construct();
	}

	public function index() 
	{
		$data = [
			'route' => $this->route,
			'breadcrumb' => [
                'Master Data' => true,
                'N4-Attendance' => true,
                'Peserta' => route($this->route.'index'),
            ],
			'employeeGroups' => EmployeeGroup::all()
		];

		return $this->view($this->route . 'index',$data);

	}

	public function create()
	{
		$data = [
			'route' => $this->route,
			'breadcrumb' => [
				'Master Data' => true,
				'index' => route($this->route.'index'),
				'create' => route($this->route.'create'),
			],
			'units' => list_unit_bagian_master(['kodeunitbagian', 'namaunitbagian'], ['kelompok' => ['KANPUS', 'GUU', 'UNIT USAHA', 'KPJ', 'TEH']]),
			'days' => list_days(),
		];

		return $this->view($this->route . 'create', $data);
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'nik'								=> 'required|min:7',
			'name'              => 'required',
			'nick_name'         => 'required',
			'employee_group_id' => 'required',
			'username'          => 'required'
		]);

		try {

			DB::beginTransaction();
			Employee::insert([
				'nik'								=> $request->input('nik'),
				'name'        			=> $request->input('name'),
				'nick_name'         => $request->input('nick_name'),
				'employee_group_id'	=> $request->input('employee_group_id'),
				'username'					=> $request->input('username'),
				'created_by'				=> Auth::user()->getUser()->username,
				'created_at'				=> date('Y-m-d H:i:s'),
			]);    
			DB::commit();

			$this->writeLog(__METHOD__ . ' >>> Berhasil menambah peserta N4 Attendance baru');
			return redirect()->route($this->route . 'index')->with('swalSuccess','Berhasil menyimpan data peserta');   

		} catch (QueryException $e) {
			DB::rollBack();
			return redirect()->back()->withError('Terjadi kesalahan saat menyimpan data, hubungi administrator');   
		}

	}

	public function edit(Request $request, $idEncrypted)
	{

		// $id = decrypt($request->input('id'));
		$id = decrypt($idEncrypted);


		$data = Employee::select('nik','name','nick_name','employee_group_id','username','imei')
		->where('id', $id)
		->first();

		$data->nama_unit = $data->simkar->unitkerja->namaunitbagian;
		$data->nama_bagian = $data->simkar->bagianx->namabagian;
		$data->nama_jabatan = $data->simkar->jabatan->jabatan;

		return response()->json($data);
	}

	public function update(Request $request, $idEncrypted)
	{

		// $id = decrypt($request->input('id'));
		$id = decrypt($idEncrypted);

		$validator = Validator::make($request->all(), [
			'nick_name'         => 'required',
			'employee_group_id' => 'required',
			'username'          => 'required'
		]);

		try {

			DB::beginTransaction();

			Employee::where('id', $id)
			->update([
				'nick_name'         => $request->input('nick_name'),
				'employee_group_id'	=> $request->input('employee_group_id'),
				'username'			=> $request->input('username'),
				'imei'				=> $request->input('imei'),
				'updated_by'		=> Auth::user()->getUser()->username,
				'updated_at'		=> date('Y-m-d H:i:s')
			]);

			if ($request->input('imei') == '' || $request->input('imei') == null) {
			Employee::where('id', $id)
			->update([
				'password'			=> null,
				'activated_at'		=> null
			]);
			}

			DB::commit();

			$this->writeLog(__METHOD__ . ' >>> Berhasil mengupdate peserta N4 Attendance');
			return redirect()->route($this->route . 'index')->with('swalSuccess','Berhasil mengupdate data peserta');  

		} catch (QueryException $e) {
			DB::rollBack();
			return redirect()->back()->withError('Terjadi kesalahan saat mengupdate data, hubungi administrator');   
		}
	}

	public function cekNik(Request $request)
	{
		// dd($request->input('nik_check'));
		$exist = Employee::select('nik')
		->where('nik', $request->input('nik_check'))
		->get();

		if (count($exist) > 0 ) {
			return response()->json(false);
		}
		return response()->json(true);
	}

	public function cekPonsel(Request $request)
	{
		$exist = Employee::where('username', $request->input('username'))->get();
		if (count($exist) > 0) {
			return response()->json(false);
		}
		return response()->json(true);
	}

	public function getTable() 
	{
		$employees = Employee::with('employeeGroup');

		return DataTables::of($employees)
		->addColumn('nama', function ($data) {
			if(!is_null($data->simkar)) {
				return $data->simkar->nama;
			} else {
				return '';
			}
		})
		->addColumn('group', function ($data) {
			return '<a href="'.route('master-data.n4attendance.groups.index').'">'.$data->employeeGroup->name.'</a>';
		})
		->addColumn('action', function ($data) {
			return '<a href="javascript:;"class="btn btn-warning btn-sm" onclick="edit(event,\''.encrypt($data->id).'\')" title="Edit Peserta"><i class="fa fa-edit"></i></a>&nbsp;<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($data->id)).'\')" title="Hapus Peserta"><i class="fa fa-trash"></i></a>';
		})
		->editColumn('unit', function($employee) {
			return $employee->simkar->unitkerja->namaunitbagian;
		})
		->editColumn('bagian', function($employee) {
			return !empty($employee->simkar->kodebagian) ? $employee->simkar->bagianx->namabagian : '';
		})
		->rawColumns(['action', 'group'])
		->make(true);
	}

	public function destroy($id, Request $request)
	{
		$id = decrypt($id);

		try {
			DB::beginTransaction();

			$deletedRows = Employee::where('id',$id)->delete();

			// Employee::destroy($id);
			DB::commit();

			$this->writeLog(__METHOD__ . ' >>> Berhasil menghapus data peserta N4 Attendance');
			// return redirect()->route($this->route . 'index')->with('swalSuccess','Berhasil menghapus data peserta');   
			return response()->json(1);

		} catch (QueryException $e) {
			DB::rollBack();
			// return redirect()->back()->withError('Terjadi kesalahan saat menghapus data, hubungi administrator');   
			return response()->json(0);
		}
	}
}
