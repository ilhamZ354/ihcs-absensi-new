<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Services\Excel\Exports\BKMExport;
use Auth;
use DB;

class BkmController extends DesktopBaseController
{
	protected $route = 'laporan.bkm.';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [
			'route' => $this->route,
		];

		if(Auth::user()->hasGroup('ADMIN_ABSENSI') || Auth::user()->hasGroup('SUPER_USER')) {
			$data['units'] = list_unit_bagian_master(['kodeunitbagian', 'namaunitbagian', 'inisial'], ['kelompok' => ['UNIT USAHA', 'TEH', 'PMT']]);
		} else {
			$data['units'] = list_unit_bagian_master(['kodeunitbagian', 'namaunitbagian', 'inisial'], ['kodeunitbagian' => Auth::user()->getUser()->kode_unit]);
		}
		return $this->view($this->route . 'index', $data);
	}

	public function export($view, $unit_usaha, $data, $tahun, $bulan) 
	{
		// return Excel::download(new BKMExport, 'invoices.xlsx');
		$filename = "bkm_{$tahun}_{$bulan}_" . $unit_usaha . "_by_eabsensi_" . time() . ".xls";
		return (new BKMExport($view, $data))->download($filename, \Maatwebsite\Excel\Excel::XLS);
	}


	public function filter(Request $request)
	{
		$role = [
			"tahun" => "required",
			"bulan" => "required"
		];

		if(Auth::user()->hasGroup('ADMIN_ABSENSI') || Auth::user()->hasGroup('SUPER_USER')) {
			$role['unit_usaha']	= "required";
		}

		$request->validate($role);

		if(Auth::user()->hasGroup('ADMIN_ABSENSI') || Auth::user()->hasGroup('SUPER_USER')) {
			$unit_usaha = $request->input('unit_usaha');
		} else {
			$unit_usaha = Auth::user()->getUser()->kode_unit;
		}

		$data = [
			/*'headers' => [
			'Company Code',
			'Tanggal',
			'Plant',
			'Afdeling/keamanan/traksi (divisi)',
			'Mandoran',
			'Pengawas Utama',
			'Mandor',
			'Krani/Operator Adm',
			'Krani Buah/Trans',
			'NIK / Employee ID',
			'NAMA_KARYAWAN',
			'Contractor',
			'Komoditi Code',
			'Target Alokasi Biaya',
			'Location Code',
			'Aktifitas',
			'Kehadiran/Attendance',
			'Clock In Date',
			'Clock In Time',
			'Clock Out Date',
			'Clock Out Time',
			'0',
			'Mandays UoM',
			'Overtime Actual',
			'Overtime Conversion',
			'Currency',
			'Nominal / Rate',
			'Quantity',
			'Qty UoM',
			'Referensi',
			'Keterangan',
			'Mekanisasi',
			'Panen buah sangat mentah',
			'Busuk tidak di berondolkan',
			'Tangkai TBS tidak berbentuk \'V\'',
			'Tangkai panjang tidak dipotong',
			'Pelepah tidak diturunkan',
			'Buah Matang tidak dipanen',
			'Buah mantang dipanen tidak diangkut ke TPH',
			'Berondolan tidak dikutip bersih',
			'TBS tidak disusun di TPH',
			'Pelepah tidak disusun digawangan mati',
			'Berondolan',
			'TBS tidak diberi Nomor',
			'Buah mentah masuk ke PKS',
			'Berondolan tertinggal di TPH',
			'Selisih TBS di PB5',
			'Lateks',
			'Base Unit of Measure',
			'Kompo',
			'Base Unit of Measure',
			'Scrap',
			'Base Unit of Measure',
			'Pungutan Tetes Sore',
			'Base Unit of Measure',
			'Kelas Deres',
			'Weight',
			'Weight UoM',
			'Kualitas Pucuk',
			'Base Unit of Measure',
			],*/
			'headers' => [
				"Company_Code" => "Company Code",
				"Tanggal" => "Tanggal",
				"Plant" => "Plant",
				"Afdeling_keamanan_traksi_(divisi)" => "Afdeling/keamanan/traksi (divisi)",
				"Mandoran" => "Mandoran",
				"Pengawas_Utama" => "Pengawas Utama",
				"Mandor" => "Mandor",
				"Krani_Operator_Adm" => "Krani/Operator Adm",
				"Krani_Buah_Trans" => "Krani Buah/Trans",
				"NIK_Employee_ID" => "NIK / Employee ID",
				"NAMA_KARYAWAN" => "NAMA_KARYAWAN",
				"namabagian" => "NAMA BAGIAN",
				"Contractor" => "Contractor",
				"Komoditi_Code" => "Komoditi Code",
				"Target_Alokasi_Biaya" => "Target Alokasi Biaya",
				"Location_Code" => "Location Code",
				"Aktifitas" => "Aktifitas",
				"Kehadiran_Attendance" => "Kehadiran/Attendance",
				"Clock_In_Date" => "Clock In Date",
				"Clock_In_Time" => "Clock In Time",
				"Clock_Out_Date" => "Clock Out Date",
				"Clock_Out_Time" => "Clock Out Time",
				"NOL" => "0",
				"Mandays_UoM" => "Mandays UoM",
				"Overtime_Actual" => "Overtime Actual",
				"Overtime_Conversion" => "Overtime Conversion",
				"Currency" => "Currency",
				"Nominal_Rate" => "Nominal / Rate",
				"Quantity" => "Quantity",
				"Qty_UoM" => "Qty UoM",
				"Referensi" => "Referensi",
				"Keterangan" => "Keterangan",
				"Mekanisasi" => "Mekanisasi",
				"Panen_buah_sangat_mentah" => "Panen buah sangat mentah",
				"Busuk_tidak_di_berondolkan" => "Busuk tidak di berondolkan",
				"Tangkai_TBS_tidak_berbentuk_'V'" => "Tangkai TBS tidak berbentuk 'V'",
				"Tangkai_panjang_tidak_dipotong" => "Tangkai panjang tidak dipotong",
				"Pelepah_tidak_diturunkan" => "Pelepah tidak diturunkan",
				"Buah_Matang_tidak_dipanen" => "Buah Matang tidak dipanen",
				"Buah_mantang_dipanen_tidak_diangkut_ke_TPH" => "Buah mantang dipanen tidak diangkut ke TPH",
				"Berondolan_tidak_dikutip_bersih" => "Berondolan tidak dikutip bersih",
				"TBS_tidak_disusun_di_TPH" => "TBS tidak disusun di TPH",
				"Pelepah_tidak_disusun_digawangan_mati" => "Pelepah tidak disusun digawangan mati",
				"Berondolan" => "Berondolan",
				"TBS_tidak_diberi_Nomor" => "TBS tidak diberi Nomor",
				"Buah_mentah_masuk_ke_PKS" => "Buah mentah masuk ke PKS",
				"Berondolan_tertinggal_di_TPH" => "Berondolan tertinggal di TPH",
				"Selisih_TBS_di_PB5" => "Selisih TBS di PB5",
				"Lateks" => "Lateks",
				"Base_Unit_of_Measure1" => "Base Unit of Measure",
				"Kompo" => "Kompo",
				"Base_Unit_of_Measure2" => "Base Unit of Measure",
				"Scrap" => "Scrap",
				"Base_Unit_of_Measure3" => "Base Unit of Measure",
				"Pungutan_Tetes_Sore" => "Pungutan Tetes Sore",
				"Base_Unit_of_Measure4" => "Base Unit of Measure",
				"Kelas_Deres" => "Kelas Deres",
				"Weight" => "Weight",
				"Weight_UoM" => "Weight UoM",
				"Kualitas_Pucuk" => "Kualitas Pucuk",
				"Base_Unit_of_Measure5" => "Base Unit of Measure",
			],
			'rows' => DB::select('exec sp_generate_bkm ?,?,?', [
				$unit_usaha,
				$request->tahun,
				toNumberOfMonth($request->bulan)
			])
		];

		// $result = "[<br>";
		// foreach ($data['headers'] as $header) {
		// 	$result .= "\"" . str_replace(" ", "_", str_replace("/", "_", str_replace(" / ", "_", $header))) . "\" => \"" . $header . "\"<br>";
		// }
		// $result .= "]";

		// echo $result;

		if(empty($data['rows']) || is_null($data['rows'])) {
			return redirect()->back()->with('swalError', 'Data pada periode yang dipilih tidak tersedia.');
		}

		return $this->export('desktop.pages.laporan.bkm.laporan', $unit_usaha, $data, $request->tahun, $request->bulan);
	}

}
