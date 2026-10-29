<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\DesktopBaseController;
use App\Models\TpTimetable;
use DataTables;
use Log;
use Auth;
use DB;

class TimetableController extends DesktopBaseController
{
	protected $route = 'master-data.timetable.';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
    	// isAllow('show', 'timetable');
		$data = [
			'route' => $this->route,
			'breadcrumb' => [
				'Master Data' => true,
				'Timetable' => route($this->route.'index'),
			],
		];
		return $this->view($this->route . 'index', $data);
	}

	public function getTable()
	{
		// isAllow('show', 'timetable');

		$timetables = TpTimetable::select('tp_timetables.*', 'u.namaunitbagian')
		->join('unitbagianmaster as u', 'tp_timetables.kode_unit', 'u.kodeunitbagian');

		return DataTables::of($timetables)
		->addColumn('status', function ($timetable) {
			return $timetable->status ? '<span class="badge badge-success">Aktif</label>' : '<span class="badge badge-danger">Tidak Aktif</label>';
		})
		->addColumn('jam_masuk', function ($timetable) {
			return date('H:i:s', strtotime($timetable->jam_masuk));
		})
		->addColumn('jam_masuk_min', function ($timetable) {
			return date('H:i:s', strtotime($timetable->jam_masuk_min));
		})
		->addColumn('jam_masuk_max', function ($timetable) {
			return date('H:i:s', strtotime($timetable->jam_masuk_max));
		})
		->addColumn('jam_pulang', function ($timetable) {
			return date('H:i:s', strtotime($timetable->jam_pulang));
		})
		->addColumn('jam_pulang_min', function ($timetable) {
			return date('H:i:s', strtotime($timetable->jam_pulang_min));
		})
		->addColumn('jam_pulang_max', function ($timetable) {
			return date('H:i:s', strtotime($timetable->jam_pulang_max));
		})
		->addColumn('satpam', function ($timetable) {
			return $timetable->satpam ? '<span class="badge badge-success">Ya</label>' : '<span class="badge badge-danger">Bukan</label>';
		})
		->addColumn('action', function ($timetable) {
			$temp = '';
			if(allow('edit', 'timetable')) {
				$temp .= ' <a href="'.route($this->route . 'edit', encrypt($timetable->id)).'" class="btn btn-secondary btn-sm" title="Edit Libur/Cuti Bersama"><i class="fa fa-edit"></i></a> ';
			} else {
				$temp .= btn_disabled('edit');
			}

			if(allow('delete', 'timetable')) {
				$temp .= '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($timetable->id)).'\')" title="Hapus Libur/Cuti Bersama"><i class="fa fa-trash"></i></a>';
			} else {
				$temp .= btn_disabled('trash');
			}
			return $temp;
		})
		->rawColumns(['satpam','status', 'action'])
		->make(true);
	}

	public function create()
	{
		$data = [
			'route' => $this->route,
			'breadcrumb' => [
				'Master Data' => true,
				'Timetable' => route($this->route.'index'),
				'Tambah Data' => route($this->route.'create'),
			],
			'units' => list_unit_bagian_master(['kodeunitbagian', 'namaunitbagian'], ['kelompok' => ['KANPUS', 'GUU', 'UNIT USAHA', 'KPJ', 'TEH']]),
			'days' => list_days(),
		];

		return $this->view($this->route . 'create', $data);
	}

	public function store(Request $request)
	{
		$request->validate([
			"unit_kerja" => "required",
			"nama_hari" => "required",
			"jam_masuk" => "required",
			"jam_masuk_min" => "required",
			"jam_masuk_max" => "required",
			"jam_pulang" => "required",
			"jam_pulang_min" => "required",
			"jam_pulang_max" => "required",
			"satpam" => "required",
			"status" => "required",
		]);

		DB::beginTransaction();

		try {
			$idTimetable = TpTimetable::insertGetId([
				'kode_unit' => $request->input('unit_kerja'),
				'dayname' => $request->input('nama_hari'),
				'jam_masuk' => $request->input('jam_masuk'),
				'jam_masuk_min' => $request->input('jam_masuk_min'),
				'jam_masuk_max' => $request->input('jam_masuk_max'),
				'jam_pulang' => $request->input('jam_pulang'),
				'jam_pulang_min' => $request->input('jam_pulang_min'),
				'jam_pulang_max' => $request->input('jam_pulang_max'),
				'satpam' => $request->input('satpam'),
				'status' => $request->input('status'),
				'created_by' => Auth::user()->getUser()->username,
				'created_at' => date('Y-m-d H:i:s'),
			]);

			DB::commit();
			Log::info(Auth::user()->getUser()->username . " berhasil menambah record timetable, id:{$idTimetable}");

			return redirect()->route($this->route . 'index')->with('swalSuccess','Berhasil menyimpan data');   
		} catch (QueryException $e) {
			Log::error($e->getMessage());
			DB::rollBack();
			return redirect()->back()->withInput()->withError('Terjadi kesalahan saat menyimpan data, hubungi administrator');   
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			DB::rollBack();
			return redirect()->back()->withInput()->withError('Terjadi kesalahan fatal saat memproses permintaan, segera hubungi administrator');   
		}

	}
}
