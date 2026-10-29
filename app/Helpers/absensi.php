<?php

use Illuminate\Support\Carbon;

if(!function_exists('get_mdb_path')) {
	function get_mdb_path() {
		return \App\Models\TrConfigApp::select('value')->where('key', 'path_mdb')->where('status', 1)->first()->value;
	}
}

if(!function_exists('sinkronisasi_peserta_mdb')) {	
	function sinkronisasi_peserta_mdb() {
		
		// $mdb_path = env('MDB_PATH', 'C:\Users\H008109001\Desktop\ATT2000.MDB');
		$mdb_path = get_mdb_path();
		$perintah = resource_path('assets'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'getpeserta.exe').' "'.$mdb_path.'"';
		$response = shell_exec($perintah);
		\Log::info($perintah);
		\Log::info('getpeserta.exe >>> ' . $response);
		return $response;
	}
}

if(!function_exists('get_id_kelompok_kerja_by_name')) {	
	function get_id_kelompok_kerja_by_name($name) {
		return \DB::table('tr_kelompok_kerjas')
			->select('id')
			->where('nama', $name)
			->first()
			->id;
	}
}

if(!function_exists('run_backup_db')) {	
	function run_backup_db() {
		$filepath = null;

        $db_host = env('DB_HOST');
        $db_name = env('DB_DATABASE');
        $db_usn = env('DB_USERNAME');
        $db_pwd = env('DB_PASSWORD');
        $username = !is_null(\Auth::user()) ? \Auth::user()->getUser()->username : 'ABSENSI_HELPER';

        $db = new \PDO("sqlsrv:server={$db_host};Database={$db_name}", $db_usn, $db_pwd);
        $stmt = $db->prepare('exec sp_backup_db ?');
        $stmt->bindParam(1, $username, \PDO::PARAM_STR);
        $exec = $stmt->execute();
        do {
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if(!is_null($results) && !empty($results)) {
                $filepath = collect($results)->first()['filepath'];
            }
        } while ($stmt->nextRowset());

        return $filepath;
	}
}

if(!function_exists('list_kode_absensi')) {
	function list_kode_absensi()
	{
		return (object) [ 
			'K' => 'jl_k',
			'WFH' => 'jl_wfh',
			'KN' => 'jl_kn',
			'L' => 'jl_l',
			'S' => 'jl_s',
			'S-1' => 'jl_s1',
			'S-2' => 'jl_s2',
			'M' => 'jl_m',
			'M-1' => 'jl_m1',
			'P-1' => 'jl_p1',
			'P-2' => 'jl_p2',
			'P-3' => 'jl_p3',
			'P-4' => 'jl_p4',
			'CB' => 'jl_cb',
			'CT' => 'jl_ct',
			'CP' => 'jl_cp',
			'CL' => 'jl_cl',
			'H-1' => 'jl_h1',
			'H-2' => 'jl_h2',
			'H-3' => 'jl_h3',
			'CH' => 'jl_ch',
			'DL' => 'jl_dl',
			'TD' => 'jl_td',
			'N/A' => 'jl_na',
		];
	}
}

if(!function_exists('is_nonptpn4')) {
	function is_nonptpn4($nik_simkar)
	{
		return \App\Models\TpPeserta::whereRaw("substring(nik_simkar, 1, 1) in ('H', 'M', 'O', 'P')")
					->where('nik_simkar', $nik_simkar)
					->count() > 0;
	}
}

if(!function_exists('cfg')) {
	function cfg($key, $replaceValue = null)
	{
		$config = \App\Models\TrConfigApp::select('value')
					->where('key', $key)
					->where('status', 1)
					->first();
		
		if(!is_null($config))
			return $config->value;
		return $replaceValue;
	}
}

if(!function_exists('get_current_mdb_path')) {
	function get_current_mdb_path()
	{
		return \App\Models\TrConfigApp::select('value')
					->where('key', 'path_mdb')
					->where('status', 1)
					->first()->value;
	}
}

if(!function_exists('notifications')) {
	function notifications():bool
	{
		return App\Models\TrNotification::where('username_target', \Auth::user()->getUser()->username)
			->where('readed', 0)
			->count() > 0;
	}
}

if(!function_exists('notif_list')) {
	function notif_list()
	{
		return \DB::table('tr_notifications as n')->select('a.aktifitas')
		->join('tp_log_aktifitas as a', 'a.id', 'n.id_log_aktifitas')
		->where('n.username_target', \Auth::user()->getUser()->username)
			->where('n.readed', 0)
			->get();
	}
}

// if(!function_exists('is_dalam_bulan_ini')) {
// 	function is_dalam_bulan_ini($tgl)
// 	{
// 		if(env('LOCK_BULAN_PENGAJUAN_FORMULIR_ABSENSI') == true) {
// 			return date('Y') == date('Y', strtotime($tgl))
// 				&& date('m') <= date('m', strtotime($tgl));
// 		} else {
// 			$bulan = [];
// 			for($bln=1; $bln <= 12; $bln++) {
// 				array_push($bulan, $bln);
// 			}
			
// 			return date('Y') == date('Y', strtotime($tgl)) 
// 				&& in_array(date('m', strtotime($tgl)), $bulan);
// 		}
// 	}
// }

if(!function_exists('is_dalam_bulan_ini')) {
	function is_dalam_bulan_ini($tgl)
	{
		return boolval(date('Y', strtotime($tgl)) == date('Y')
					&& date('m', strtotime($tgl)) == date('m'));	
	}
}

if(!function_exists('is_periode_masih_terbuka')) {
	function is_periode_masih_terbuka($tgl)
	{
		if(env('LOCK_BULAN_PENGAJUAN_FORMULIR_ABSENSI') == true) {
			return \App\Models\TpLogUploadAbsensi::where('tahun', date('Y', strtotime($tgl)))
				->where('bulan', date('m', strtotime($tgl)))
				->count() == 0;
		}
		return false;
	}
}


if(!function_exists('is_periode_backdate_dibuka')) {
	function is_periode_backdate_dibuka($tgl_mulai, $tgl_selesai)
	{
		$result = false;

		if(env('BACKDATE_CONFIG_STATUS') == true) {
			$unit = Auth::user()->getUser()->kode_unit;
			$bagian = Auth::user()->getUser()->kode_bagian;

			$query = \DB::table('tr_buka_formulir_backdates')
			->where('kodeunitbagian', $unit);

			if($unit == '00')
				$query->where('kodebagian', $bagian);

			$query->whereRaw(date('\'Y-m-d', strtotime($tgl_mulai)) . '\' between tgl_ubah_mulai and tgl_ubah_selesai')
			->whereRaw('\''.date('Y-m-d', strtotime($tgl_selesai)) . '\' between tgl_ubah_mulai and tgl_ubah_selesai')
			->whereRaw('getdate() between dibuka_dari and dibuka_sampai')
			->where('status', 1);

			$result = $query->count() > 0;
		}
		
		return boolval($result);
	}
}


if(!function_exists('is_bulan_lalu')) {
	function is_bulan_lalu($tgl) {
		$_bulan = date('m', strtotime($tgl));
		$_tahun = date('Y', strtotime($tgl));

		$bulan = 0;
		$tahun = 0;

		if(date('m') == 1) {
			$bulan = 12;
			$tahun = (date('Y') - 1);
		} else {
			$bulan = (date('m')-1);
			$tahun = date('Y');
		}

		return $bulan == $_bulan && $tahun == $_tahun;
	}
}

if(!function_exists('cek_kebenaran_pengajuan_KERJA')) {
	function cek_kebenaran_pengajuan_KERJA($nik, $tglMulai, $tglSelesai, $kode)
	{
		if($kode == 'K') {
			if(in_array(get_kelompok_unit_by_nik($nik), ['KANPUS', 'KPJ'])) {

				$listDays = \DB::select("select case when sabtu = 1 or libur  = 1 then 1 else 0 end libur from (
						select a.tgl, case when datename(dw, a.tgl) = 'Saturday' then 1 else 0 end [sabtu], case when b.tgl is null then 0 else 1 end [libur] 
						from dbo.fn_get_list_date_range(?, dateadd(day, 1, ?)) a
						left join  tr_libur_cuti_bersamas b on  b.tgl = a.tgl 
					) a", [
					date('Y-m-d', strtotime($tglMulai)),
					date('Y-m-d', strtotime($tglSelesai))
				]);

			} else {
				$listDays = \DB::select("select case when b.tgl is null then 0 else 1 end libur
					from dbo.fn_get_list_date_range(?, dateadd(day, 1, ?)) a
					left join tr_libur_cuti_bersamas b on b.tgl = a.tgl", [
						date('Y-m-d', strtotime($tglMulai)),
						date('Y-m-d', strtotime($tglSelesai))
					]);
			}
			$list = collect($listDays)->pluck('libur')->toArray();
			return !in_array(0, $list);
		}
		return true;
	}
}

if(!function_exists('cek_kebenaran_pengajuan_KERJA_SPECIAL')) {
	function cek_kebenaran_pengajuan_KERJA_SPECIAL($nik, $tglMulai, $tglSelesai, $kode)
	{
		if(in_array($kode, ['KK', 'WFH', 'KN'])) {
			if(in_array(get_kelompok_unit_by_nik($nik), ['KANPUS', 'KPJ'])) {

				$listDays = \DB::select("select case when sabtu = 1 or libur  = 1 then 1 else 0 end libur from (
						select a.tgl, case when datename(dw, a.tgl) = 'Saturday' then 1 else 0 end [sabtu], case when b.tgl is null then 0 else 1 end [libur] 
						from dbo.fn_get_list_date_range(?, dateadd(day, 1, ?)) a
						left join  tr_libur_cuti_bersamas b on  b.tgl = a.tgl 
					) a", [
					date('Y-m-d', strtotime($tglMulai)),
					date('Y-m-d', strtotime($tglSelesai))
				]);

			} else {
				$listDays = \DB::select("select case when b.tgl is null then 0 else 1 end libur
					from dbo.fn_get_list_date_range(?, dateadd(day, 1, ?)) a
					left join tr_libur_cuti_bersamas b on b.tgl = a.tgl", [
						date('Y-m-d', strtotime($tglMulai)),
						date('Y-m-d', strtotime($tglSelesai))
					]);
			}
			$list = collect($listDays)->pluck('libur')->toArray();
			return !in_array(1, $list);
		}
		return true;
	}
}

if(!function_exists('is_periode_generate_summary')) {
	function is_periode_generate_summary()
	{	
		return true;

		// return date('d') >= 1 && date('d') <= 5;
	}
}

if(!function_exists('att_machine_list')) {
	function att_machine_list()
	{	
		return [
			'KEUANGAN' => '10.20.103.19', //update jan 2024
			'PEMASARAN' => '10.20.113.173', //update agustus 2025
			'PENGADAAN' => '10.20.110.138',  //update september 2026
			'UMUM' => '10.20.117.229',  //update agustus 2025
			'SEKPER' => '10.20.14.37', //alat rusak
			'GUI' => '10.21.40.122', // update Februari 2026
			'GUII' => '10.21.58.101', // update desember 2025
			'GUIII' => '10.21.70.7', // update september 2026 
			'KANDIR_SDM' => '10.20.109.174', // baru november 2025 
			'KANDIR_SEKPER' => '10.20.114.97', // baru november 2025
			'KANDIR_TEKPOL' => '10.20.105.75', // baru november 2025
			'KANDIR_SPI' => '10.20.107.196', // baru november 2025
			'KANDIR_TANAMAN' => '10.20.118.125', // baru november 2025
			'KANDIR_HPS' => '10.20.116.146' // baru november 2025
		];
	}
}

if(!function_exists('is_libur_cuti_bersama')) {
	function is_libur_cuti_bersama($tgl):bool
	{	
		return \App\Models\TrLiburCutiBersama::where('tgl', date('Y-m-d', strtotime($tgl)))->count() > 0;
	}
}

if(!function_exists('list_unit_mesin')) {
	function list_unit_mesin()
	{	
		return \DB::table('unitbagianmaster')->select('kodeunitbagian')
		->whereIn('kelompok', ['kanpus', 'guu', 'kpj'])
		->where('status',1)		
		->get()->pluck('kodeunitbagian')->toArray();
	}
}

if(!function_exists('is_unit_menggunakan_mesin')) {
	function is_unit_menggunakan_mesin($kodeunitbagian)
	{
		return in_array($kodeunitbagian, list_unit_mesin());
	}
}

if(!function_exists('is_user_menggunakan_mesin')) {
	function is_user_menggunakan_mesin($nik)
	{
		$kodeunitbagian = get_unit_kerja_by_nik_simkar($nik);
		if(!is_null($kodeunitbagian)) {
			return in_array($kodeunitbagian, list_unit_mesin());
		} else{
			throw new \Exception("Karyawan tidak memiliki unit kerja", 1);
		}
	}
}

if(!function_exists('list_tahun_bulan_summary')) {
	function list_tahun_bulan_summary()
	{
		return \DB::connection('dev_absensi')->table('tr_summary_absensi_bulanans')->select('tahun', 'bulan')->distinct()->get();
	}
}

if(!function_exists('nama_kode_absensi')) {
	function nama_kode_absensi($kode, $tahun, $bulan, $date)
	{
		$date = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-'.$date));
		if(is_null($kode) || empty($kode)) {
			return null;
		} else if($kode == "L" && date('w', strtotime($date)) != 6) {
			$uraian = \DB::connection('dev_absensi')->table('tr_libur_cuti_bersamas')->select('uraian')
			->where('tgl', $date)			
			->first();

			if(!is_null($uraian))
				return $uraian->uraian;
			else
				return null;
		} else {
			return \DB::connection('dev_absensi')->table('tr_jenis_absens')->select('nama')->where('kode', $kode)->first()->nama;
		}
	}
}

if(!function_exists('ihcs_decrypt')) {
	function ihcs_decrypt($encrypted)
	{
		$cipher = 'AES-256-CBC';		
		$key = '9d935190a5dad47733667a6c612dcf1f';
		$encrypter = new \Illuminate\Encryption\Encrypter($key, $cipher);
		$result = $encrypter->decrypt($encrypted);
		return $result;
	}
}

if(!function_exists('ihcs_encrypt')) {
	function ihcs_encrypt($plaintext)
	{
		$cipher = 'AES-256-CBC';		
		$key = '9d935190a5dad47733667a6c612dcf1f';
		$encrypter = new \Illuminate\Encryption\Encrypter($key, $cipher);
		$result = $encrypter->encrypt($plaintext);
		return $result;
	}
}

if(!function_exists('get_kelompok_unit_by_nik')) {
	function get_kelompok_unit_by_nik($nik)
	{
		$query = "select u.kelompok from (select unit from karyawan_ptpn4 where nik = ? union select unit from tm_karyawan_nonptpn4 where nik = ?) k
		join unitbagianmaster u on u.kodeunitbagian = k.unit";

		return collect(DB::select($query, [$nik, $nik]))->first()->kelompok;
	}
}

if(!function_exists('is_history_exists')) {
	function is_history_exists($tahun, $bulan)
	{
		return DB::table('T_HISTORY_KARYAWAN_BULANAN')->whereTahun($tahun)->whereBulan($bulan)->count() > 0;
	}
}

if(!function_exists('get_kuota_cuti')) {
	function get_kuota_cuti($jenis, $nik, $tgl_mulai, $tgl_selesai)
	{
		/*declare 
		@nik varchar(15) = '177402001'
		, @s date = '2019-03-25'
		, @e date = '2019-03-28'

		select kuota - terpakai [sisa]
		from tr_kuota_cuti_tahunans k
		where k.nik_simkar = @nik
		and @s between tgl_mulai and tgl_berakhir
		and @e between tgl_mulai and tgl_berakhir*/

		if($jenis == 'CT') {
			$sqlKuota = DB::table('tr_kuota_cuti_tahunans');
		}else if($jenis == 'CP') {
			$sqlKuota = DB::table('tr_kuota_cuti_panjangs');
		}
		else {
			return true;
		} 

		$sqlKuota->select(DB::raw("(kuota - terpakai) as sisa"))
		->where('nik_simkar', $nik)
		->whereRaw("'". date('Y-m-d', strtotime($tgl_mulai)) . "' between tgl_mulai and tgl_berakhir")
		->whereRaw("'". date('Y-m-d', strtotime($tgl_selesai)) . "' between tgl_mulai and tgl_berakhir");

		$kuota = !empty($sqlKuota->first())  ? $sqlKuota->first()->sisa : 0;
		return $kuota;
	}
}

if(!function_exists('exist_on_header')) {
	function exist_on_header($nik_simkar, $dbTglMulai, $dbTglSelesai)
	{
		$existOnHeader =  DB::table('tp_formulir_headers')
        ->where('nik_simkar',$nik_simkar)
        ->where(function($query) use($dbTglMulai, $dbTglSelesai) {
        	$query->whereRaw("'". $dbTglMulai . "' <= tgl_mulai and '". $dbTglSelesai . "' >= tgl_selesai")
        	->orWhereRaw("'". $dbTglMulai . "' between tgl_mulai and tgl_selesai")
        	->orWhereRaw("'". $dbTglSelesai . "' between tgl_mulai and tgl_selesai");
        })
        ->where('status',1)
        ->get();

        if (count($existOnHeader) > 0) {
        	return true;
        }
        else {
        	return false;
        }
	}
}

if(!function_exists('get_mobile_attendance_from_api')) {
	function get_mobile_attendance_from_api($year, $month)
	{
		$this->curl(env('MOBILE_ATTENDANCE_API_URL', "https://kindi.id/apiptpn4/?option=vw_absensi&task=data_absensi"));
		$startDate = date('Y-m-d', strtotime($year.'-'.$month.'-01'));
		$endDate = date('Y-m-d', strtotime("-1 days", strtotime("+1 months", strtotime($year.'-'.$month.'-01'))));

		$res =  collect(json_decode($this->body))->where('tanggal', '>=', $startDate)->where('tanggal', '<=', $endDate)
		->sortBy('tanggal')
		->sortBy('nama');

		return $res;
	}
}

if(!function_exists('mdb_connect')) {
	function mdb_connect() {
		// dd(ip_address('localhost'));
		// if(!ip_address('localhost')) {
		// 	return null;
		// }

		$mdb_path = "F:\ptpn4\personalia\mdb\ATT2000.MDB";
		$drv = "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};DBQ={$mdb_path};";
		try {
			$db = new PDO($drv);
			// $db = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};DBQ=E:\mdbpath\ATT2000.MDB;");
			return $db;			
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			return null;
		}
	}
}

if(!function_exists('mdb_get_users')) {
	function mdb_get_users() {

		$ch = curl_init('http://10.10.5.175:2019/users');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, []);

		$result = curl_exec($ch);
		if (curl_errno($ch))
			throw new \Exception(curl_error($ch));

		curl_close($ch);

		// return json_decode($result);
		return $result;
	}
}

if(!function_exists('mdb_get_departments')) {
	function mdb_get_departments():array {
		$mdb = mdb_connect();
        $sql  = "SELECT DEPTID, DEPTNAME, SUPDEPTID FROM DEPARTMENTS ORDER BY DEPTID ASC";
        $result = $mdb->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
	}
}

if(!function_exists('mdb_get_absensi')) {
	function mdb_get_absensi($thn, $bln, $pins = []):array {
		if($pins) {
			$pins = "'".implode("','", $pins)."'";
		}
		$mdb = mdb_connect();
		
		$sql  = "SELECT USERINFO.Badgenumber as [pin], CHECKTIME as [datetime], sn as [machine] 
		FROM CHECKINOUT 
		INNER JOIN USERINFO ON CHECKINOUT.USERID = USERINFO.USERID 
        WHERE CHECKINOUT.CHECKTIME >= #{$thn}-{$bln}-01 00:00:00# ";
        if($pins) {
        	$sql .= "AND USERINFO.Badgenumber In($pins) ";
        }
        $sql .= "ORDER BY CHECKINOUT.CHECKTIME DESC";

        $result = $mdb->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
	}
}

if(!function_exists('get_unit_kerja_by_nik_simkar')) {
	function get_unit_kerja_by_nik_simkar($nik) {
		$user = \DB::table('karyawan_ptpn4')->select('unit')
		->where('nik', $nik)
		->first();

		$userNonPtpn4 = \DB::table('tm_karyawan_nonptpn4')->select('unit')
		->where('nik', $nik)
		->first();

		if(!is_null($user)) {
			return $user->unit;
		} else if(!is_null($userNonPtpn4)) {
			return $userNonPtpn4->unit;
		}

		return null;
	}
}

/**
    * 1. Pengajuan hanya boleh dlm rentang tgl 26 (Bulan -1) s/d 25 Bulan ini (kecuali jika diizinkan oleh admin)
    * @param date $tgl_mulai
    * @param date $tgl_selesai
    * @return bool
    */
	if(!function_exists('is_pengajuan_dlm_periode')) {
		function is_pengajuan_dlm_periode($tgl_mulai, $tgl_selesai) {
			$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai));
			$tgl_selesai = date('Y-m-d', strtotime($tgl_selesai));

			$date = get_date_min_max();

			return ($tgl_mulai >= $date->min && $tgl_mulai <= $date->max) || is_periode_backdate_dibuka($tgl_mulai, $tgl_selesai);
		}
	}

    if(!function_exists('is_pengajuan_dlm_periode_nonmesin')) {
    	function is_pengajuan_dlm_periode_nonmesin($tgl_mulai, $tgl_selesai) {
    		$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai));
    		$tgl_selesai = date('Y-m-d', strtotime($tgl_selesai));

    		$date = get_date_min_max_nonmesin($tgl_mulai, $tgl_selesai);

    		return ($tgl_mulai >= $date->min && $tgl_mulai <= $date->max) || is_periode_backdate_dibuka($tgl_mulai, $tgl_selesai);
    	}
    }

    /**
    * Atas kesepakatan Pak Iwen dan Bu Vadila, tgl 18 Februari 2020, maka:
    * 
    * Pengisian tgl 26 s/d 31 bulan lalu hanya diperbolehkan hingga tgl 10 bulan ini
    * Pengisian tgl 01 s/d 25 bulan ini diperbolehkan dari tgl 01 s/d 25
    * Pengisian tgl 26 s/d 31 bulan ini diperbolehkan dari tgl 26 bulan ini s/d  tgl 10 bulan depan
    * dst
    */
    if(!function_exists('get_date_min_max')) {
    	function get_date_min_max() {
    		$date = new \stdClass();
    		if(date('d') >= 1 && date('d') <= 10) {
    			$date->min = date('Y-m', strtotime("-1 months")) . '-21';
    			$date->max = date('Y-m') . '-20';
    		} else if(date('d') > 10 && date('d') <= 20) {
    			$date->min = date('Y-m') . '-01';
    			$date->max = date('Y-m') . '-20';
    		} else if(date('d') > 20 && date('d') <= 31) {
    			$date->min = date('Y-m') . '-21';
    			$date->max = date('Y-m', strtotime("+1 months")) . '-20';
    		} else {
    			$date->min = date('Y-m-d');
    			$date->max = date('Y-m-d');
    		}
    		return $date;        
    	}
    }

    if(!function_exists('get_date_min_max_nonmesin')) {
    	function get_date_min_max_nonmesin($tgl_mulai, $tgl_selesai) {
    		$date = new \stdClass();

    		$tgl = date('d');        
    		$bulan = date('m');        
    		$tahun = date('Y');

    		$bulanDiizinkan = 0;
    		$tahunDiizinkan = 0;

    		if($bulan == 1) {
    			$bulanDiizinkan = 12;
    			$tahunDiizinkan = $tahun - 1;
    		} else {
    			$bulanDiizinkan = $bulan - 1;            
    			$tahunDiizinkan = $tahun;
    		}

        	// Dari tgl 1 m+0 sampai tgl 5m+1 
    		if($tgl <= 5) {
            //pengisian formulir absensi diperbolehkan untuk bulan lalu            
    			$date->min = date('Y-m-d', strtotime($tahunDiizinkan.'-'.$bulanDiizinkan.'-01'));
            // $date->max = date('Y-m-t', strtotime($tahunDiizinkan.'-'.$bulanDiizinkan.'-01'));
    			$date->max = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-01'));
    		} else {
    			$date->min = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-01'));
    			$date->max = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-01'));
    		}

    		return $date;        
    	}
    }

