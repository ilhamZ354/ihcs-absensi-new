<?php

use Illuminate\Database\Seeder;

class SpGenerateDetailKehadiranHarianNew extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_detail_kehadiran_harian_new') IS NOT NULL) DROP PROCEDURE sp_generate_detail_kehadiran_harian_new");
        DB::statement("CREATE PROCEDURE sp_generate_detail_kehadiran_harian_new
			@tgl date
		AS
		BEGIN

			declare @s date, @e date
			set @s = @tgl
			set @e = dateadd(day, 1, @s)

			update hh 
				set hh.checkin_time = a.checkin_time, hh.checkout_time = a.checkout_time
			from (
				select nik_simkar
					, dt.tgl
					, min(checkin_time) checkin_time
					, max(checkout_time) checkout_time
					, case 
							when ((select count(*) from tp_formulir_headers fh where fh.nik_simkar = a.nik_simkar and fh.status = 1 and dt.tgl between fh.tgl_mulai and fh.tgl_selesai) > 0) then
								(select fh.kode_jenis_absen from tp_formulir_headers fh where fh.nik_simkar = a.nik_simkar and fh.status = 1 and dt.tgl between fh.tgl_mulai and fh.tgl_selesai)
							when ((select count(*) from tr_libur_cuti_bersamas lb where dt.tgl = lb.tgl and lb.is_cb = 1) > 0) and dt.tgl <= @s then 'CB'
							when ((select count(*) from tr_libur_cuti_bersamas lb where dt.tgl = lb.tgl and lb.is_cb = 0) > 0) and dt.tgl <= @s then 'L'
							when min(checkin_time) is not null or max(checkout_time) is not null then 'K' 
						end keterangan
					from (

						-- START ABSENSI UPLOAD
						select 
							pin
							, nik_simkar
							, tgl
							, jam_masuk
							, jam_pulang
							, checkin_time
							, checkout_time
						from tr_absensi_details ad 
						where ad.tgl = @s
						-- END ABSENSI UPLOAD
						union
						-- START ABSENSI MESIN REALTIME
						select 
							pin
							, nik_simkar
							, tgl
							, null jam_masuk
							, null jam_pulang
							, convert(time, check_in) checkin_time
							, convert(time, check_out) checkout_time
						from v_att_log_dailies vd
						where vd.tgl = @s
						-- END ABSENSI MESIN REALTIME
						union
						-- START ABSENSI MESIN REALTIME
						select 
							pin
							, nik_simkar
							, tgl
							, null jam_masuk
							, null jam_pulang
							, convert(time, check_in) checkin_time
							, convert(time, check_out) checkout_time
						from v_absensi_mobile_kanpus mk
						where mk.tgl = @s
						-- END ABSENSI MESIN REALTIME
					) a
				inner join dbo.fn_get_list_date_range(@s, @e) as dt on dt.tgl = a.tgl
				left join v_history_karyawan k on k.nik = a.nik_simkar and k.tahun = year(dt.tgl) and k.bulan = month(dt.tgl)
				left join tp_timetables tb on tb.kode_unit = k.unit and tb.dayname = datename(dw, dt.tgl)
				where tb.jam_masuk is not null and tb.jam_pulang is not null
				and k.kodejabatan not in (select distinct kodejabatan from tm_jabatan where jabatan like '%satpam%' and isactive = 'Y')
				group by a.pin, a.nik_simkar, dt.tgl, k.unit, k.kodejabatan, tb.jam_masuk, tb.jam_masuk_min, tb.jam_masuk_max, tb.jam_pulang, tb.jam_pulang_min, tb.jam_pulang_max
			) a
			inner join tp_kehadiran_harians hh on hh.nik_simkar = a.nik_simkar and hh.tgl = a.tgl

			insert into tp_kehadiran_harians (nik_simkar, unit, tgl, jam_masuk, jam_masuk_min, jam_masuk_max, jam_pulang, jam_pulang_min, jam_pulang_max, checkin_time, checkout_time, keterangan)
			select * from (
				select nik_simkar
					, k.unit
					, dt.tgl
					, tb.jam_masuk
					, tb.jam_masuk_min
					, tb.jam_masuk_max
					, tb.jam_pulang
					, tb.jam_pulang_min
					, tb.jam_pulang_max
					, min(checkin_time) checkin_time
					, max(checkout_time) checkout_time
					, case 
							when ((select count(*) from tp_formulir_headers fh where fh.nik_simkar = a.nik_simkar and fh.status = 1 and dt.tgl between fh.tgl_mulai and fh.tgl_selesai) > 0) then
								(select fh.kode_jenis_absen from tp_formulir_headers fh where fh.nik_simkar = a.nik_simkar and fh.status = 1 and dt.tgl between fh.tgl_mulai and fh.tgl_selesai)
							when ((select count(*) from tr_libur_cuti_bersamas lb where dt.tgl = lb.tgl and lb.is_cb = 1) > 0) and dt.tgl <= @s then 'CB'
							when ((select count(*) from tr_libur_cuti_bersamas lb where dt.tgl = lb.tgl and lb.is_cb = 0) > 0) and dt.tgl <= @s then 'L'
							when min(checkin_time) is not null or max(checkout_time) is not null then 'K' 
						end keterangan
					from (

						-- START ABSENSI UPLOAD
						select 
							pin
							, nik_simkar
							, tgl
							, jam_masuk
							, jam_pulang
							, checkin_time
							, checkout_time
						from tr_absensi_details ad 
						where ad.tgl = @s
						-- END ABSENSI UPLOAD
						union
						-- START ABSENSI MESIN REALTIME
						select 
							pin
							, nik_simkar
							, tgl
							, null jam_masuk
							, null jam_pulang
							, convert(time, check_in) checkin_time
							, convert(time, check_out) checkout_time
						from v_att_log_dailies vd
						where vd.tgl = @s
						-- END ABSENSI MESIN REALTIME
						union
						-- START ABSENSI MESIN REALTIME
						select 
							pin
							, nik_simkar
							, tgl
							, null jam_masuk
							, null jam_pulang
							, convert(time, check_in) checkin_time
							, convert(time, check_out) checkout_time
						from v_absensi_mobile_kanpus mk
						where mk.tgl = @s
						-- END ABSENSI MESIN REALTIME
					) a
				inner join dbo.fn_get_list_date_range(@s, @e) as dt on dt.tgl = a.tgl
				left join v_history_karyawan k on k.nik = a.nik_simkar and k.tahun = year(dt.tgl) and k.bulan = month(dt.tgl)
				left join tp_timetables tb on tb.kode_unit = k.unit and tb.dayname = datename(dw, dt.tgl)
				where tb.jam_masuk is not null and tb.jam_pulang is not null
				and k.kodejabatan not in (select distinct kodejabatan from tm_jabatan where jabatan like '%satpam%' and isactive = 'Y')
				group by a.pin, a.nik_simkar, dt.tgl, k.unit, k.kodejabatan, tb.jam_masuk, tb.jam_masuk_min, tb.jam_masuk_max, tb.jam_pulang, tb.jam_pulang_min, tb.jam_pulang_max
			) a
			where not exists (
				select * from tp_kehadiran_harians hh
				where hh.nik_simkar = a.nik_simkar
				and hh.tgl = a.tgl
			)

		END;");
    }
}
