<?php

namespace App\Http\Controllers\MasterData\N4Attendance;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Attendance\Attendance;
use App\Models\Attendance\Employee;
use DataTables;

class AttendanceController extends DesktopBaseController
{
	protected $route = 'master-data.n4attendance.kehadiran.';

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
				'Kehadiran' => route($this->route.'index'),
			]
		];

		return $this->view($this->route . 'index',$data);
	}

	/**
     * Display datatablses
     *
     * @return \Illuminate\Http\Response
     */
	public function getTable()
	{
		return DataTables::of(Attendance::query())
		->editColumn('check_in', function($att) {
			return $att->check_in ? Carbon::parse($att->check_in)->format('H:i:s') : null;
		})
		->editColumn('check_out', function($att) {
			return $att->check_out ? Carbon::parse($att->check_out)->format('H:i:s') : null;
		})
		->addColumn('coordinate_in', function ($att) {
			return '<a href="javascript:;" onclick="showMap(event, \''.route($this->route . 'map', [encrypt($att->id), 'in']).'\')" title="Tampilkan koordinat dalam peta">'.$att->coordinate_in.'</a>';
		})
		->addColumn('coordinate_out', function ($att) {
			return '<a href="javascript:;" onclick="showMap(event, \''.route($this->route . 'map', [encrypt($att->id), 'out']).'\')" title="Tampilkan koordinat dalam peta">'.$att->coordinate_out.'</a>';
		})
		->rawColumns(['coordinate_in', 'coordinate_out'])
		->make(true);
	}

	public function map($idEncrypted, $state)
	{
		$att = Attendance::find(decrypt($idEncrypted));
		$employee = Employee::with('employeeGroup')->where('nik', $att->nik)->first();
		
		$data = [
			'att' => $att,
			'lokasi' => !empty($employee->employeeGroup) ? $employee->employeeGroup->location : null,
		];
		return $this->view($this->route . 'map', $data);
	}
}
