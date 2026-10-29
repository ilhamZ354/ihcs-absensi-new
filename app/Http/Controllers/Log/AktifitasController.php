<?php

namespace App\Http\Controllers\Log;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\TpLogAktifitas;
use DataTables;
use Auth;
use DB;

class AktifitasController extends DesktopBaseController
{
	protected $route = 'log.aktifitas.';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [
			'route' => $this->route,
			'breadcrumb' => [
				'Log' => true,
				'Aktifitas' => route($this->route.'index'),
			],
		];

		return $this->view($this->route . 'index', $data);
	}

	public function getTable()
	{
		$logs = TpLogAktifitas::select('tp_log_aktifitas.id', DB::raw("isnull(u.nama, tp_log_aktifitas.username) as nama"), 'tp_log_aktifitas.aktifitas', 'tp_log_aktifitas.created_by', 'tp_log_aktifitas.created_at')
				->leftjoin('v_list_user_ihcs as u', 'u.username', 'tp_log_aktifitas.username');
		return DataTables::of($logs)
		->filterColumn('nama', function($query, $keyword) {
			$query->whereRaw("isnull(u.nama, tp_log_aktifitas.username) like ?", ["%{$keyword}%"]);
		})
		->make(true);
	}

}
