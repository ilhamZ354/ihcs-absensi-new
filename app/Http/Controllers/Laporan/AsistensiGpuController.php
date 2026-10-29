<?php

namespace App\Http\Controllers\Laporan;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Services\Excel\Exports\AsistensiGpuExport;
use Maatwebsite\Excel\Facades\Excel;

class AsistensiGpuController extends DesktopBaseController
{
	protected $route = 'laporan.asistensi-gpu.';

    public function index()
	{
		$data = [
			'route' => $this->route,
		];

		return $this->view($this->route . 'index', $data);
	}

	public function process(Request $request)
	{
		$request->validate([
			"tahun" => "required",
			"bulan" => "required"
		]);

		$rows = collect(DB::select("select * from dbo.fn_generate_laporan_kehadiran_asistensi_logistik_guu1_izmi(?, ?)", [$request->tahun, toNumberOfMonth($request->bulan)]));   
        return Excel::download(new AsistensiGpuExport($rows), strtoupper("laporan_asistensi_gpu_hari_sabtu_{$request->bulan}_{$request->tahun}.xlsx"));
	}

}
