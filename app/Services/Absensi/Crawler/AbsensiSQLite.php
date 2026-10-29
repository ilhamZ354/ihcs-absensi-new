<?php

namespace App\Services\Absensi\Crawler;

use DB;

class AbsensiSQLite {

	public static function getAbsensiMesinByDate($mulai, $selesai = '') {
		$mulai = date('Y-m-d', strtotime($mulai));

		if(!$selesai) {
			$selesai = $mulai;
		} else {
			$selesai = date('Y-m-d', strtotime($selesai));
		}

		$attLogs = DB::connection('N4_ZKTime')
		->table('AttLog')
		->whereRaw("date(DateTime) between '{$mulai}' and '{$selesai}'")
		->orderBy('DateTime', 'asc')
		->get();

		return self::saveUniqueAttLogToIHCS($attLogs, $mulai, $selesai);
	}

	public static function saveUniqueAttLogToIHCS($attLogs, $mulai, $selesai) {		
		$readyLogs = $attLogs->map(function ($row) {
			return collect($row)
			->only(['PIN', 'DateTime', 'Machine']);
		});

		DB::connection('dev_absensi')->beginTransaction();
		try {
			foreach ($readyLogs as $row) {
				DB::connection('dev_absensi')->table('temp_att_log_dailies_sqlite')->insert($row->toArray());
			}
			DB::connection('dev_absensi')->commit();
			\Log::info("Sinkronisasi SQLite dengan sistem IHCS ({$mulai} s/d {$selesai}) berhasil.");
		} catch (\Exception $e) {
			\Log::error("Sinkronisasi SQLite dengan sistem IHCS ({$mulai} s/d {$selesai}) gagal.");
			\Log::error($e->getMessage());
			DB::connection('dev_absensi')->rollBack();
		}

		DB::connection('dev_absensi')->statement("exec sp_new_sinkronisasi_sqlite_att_log");

		DB::connection('N4_ZKTime')
		->statement("
			DELETE FROM _AttLog_old_20190715; 
			DELETE FROM AttLog; 
			VACUUM; 
			REINDEX;
			DELETE FROM sqlite_sequence WHERE name LIKE '%AttLog%'; 
			DELETE FROM sqlite_sequence WHERE name LIKE '%UserInfo%';
			");
	}

}