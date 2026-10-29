<?php

use Illuminate\Database\Seeder;

class SpGenerateTelatDatangCepatPulang extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_telat_datang_cepat_pulang') IS NOT NULL) DROP PROCEDURE sp_generate_telat_datang_cepat_pulang");
        DB::statement("CREATE PROCEDURE sp_generate_telat_datang_cepat_pulang
			@tahun smallint, @bulan smallint
		AS
		BEGIN
			select 
			pin, tgl, a.jam_masuk, a.jam_pulang, checkin_time, checkout_time, dbo.fn_get_durasi_dalam_menit(telat_datang) [telat_datang], dbo.fn_get_durasi_dalam_menit(cepat_pulang) [cepat_pulang]
			into #tmp_absensi_details
			from (
			select 
			a.pin, a.tgl, a.jam_masuk, a.jam_pulang, a.checkin_time, a.checkout_time
			, case when (a.checkin_time is null and a.checkout_time is not null) then 30 when datediff(minute, a.jam_masuk, a.checkin_time) > 0 then abs(datediff(minute, a.jam_masuk, a.checkin_time)) else null end [telat_datang]
			, case 
					when (a.checkout_time is null and a.checkin_time is not null) then 30 
					when datediff(minute, a.jam_pulang, a.checkout_time) < 0 then abs(datediff(minute, a.jam_pulang, a.checkout_time))
					else null 
				end [cepat_pulang]
			from tr_absensi_details a
			join tp_pesertas p on p.pin = a.pin
			join tm_unit_kelompok_kerjas k on k.kodeunitbagian = p.kodeunitbagian
			where p.satpam = k.satpam and year(a.tgl) = @tahun and month(a.tgl) = @bulan
			)a
			
			update a set a.telat_datang = t.telat_datang, a.cepat_pulang = t.cepat_pulang from #tmp_absensi_details t
			join tr_absensi_details a on a.pin = t.pin and a.tgl = t.tgl
			drop table #tmp_absensi_details 
		END;");
    }
}
