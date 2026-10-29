<?php

use Illuminate\Database\Seeder;

class SpGetSummaryAbsensiHarian extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_get_summary_absensi_harian') IS NOT NULL) DROP PROCEDURE sp_get_summary_absensi_harian");
        DB::statement("CREATE PROCEDURE [dbo].[sp_get_summary_absensi_harian]
			@tahun smallint
			, @bulan smallint
			, @nik varchar(15)
		AS
		BEGIN

		if((select count(*) from tr_summary_absensi_bulanans where tahun=@tahun and bulan = @bulan and nik_simkar = @nik) > 0) begin
			select id_peserta [pin], nik_simkar, tahun, bulan
				,[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16],[17],[18],[19],[20],[21],[22],[23],[24],[25],[26],[27],[28],[29],[30],[31]
			from tr_summary_absensi_bulanans where tahun = @tahun and bulan = @bulan and nik_simkar = @nik;
		end
		else if((select count(*) from tp_att_log_dailies where year(datetime) = @tahun and month(datetime) = @bulan) > 0) begin

			declare 
				@s date = convert(date, cast(@tahun as varchar) +'-'+ cast(@bulan as varchar) + '-01')
				, @d date = dateadd(month, 1, convert(date, cast(@tahun as varchar) +'-'+ cast(@bulan as varchar) + '-01'))


			select 
			pin, nik_simkar, tahun, bulan
			, case when [1] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-1') between tgl_mulai and tgl_selesai) > 0) then (select top 1 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-1') between tgl_mulai and tgl_selesai) else [1] end [1]
			, case when [2] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-2') between tgl_mulai and tgl_selesai) > 0) then (select top 2 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-2') between tgl_mulai and tgl_selesai) else [2] end [2]
			, case when [3] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-3') between tgl_mulai and tgl_selesai) > 0) then (select top 3 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-3') between tgl_mulai and tgl_selesai) else [3] end [3]
			, case when [4] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-4') between tgl_mulai and tgl_selesai) > 0) then (select top 4 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-4') between tgl_mulai and tgl_selesai) else [4] end [4]
			, case when [5] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-5') between tgl_mulai and tgl_selesai) > 0) then (select top 5 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-5') between tgl_mulai and tgl_selesai) else [5] end [5]
			, case when [6] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-6') between tgl_mulai and tgl_selesai) > 0) then (select top 6 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-6') between tgl_mulai and tgl_selesai) else [6] end [6]
			, case when [7] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-7') between tgl_mulai and tgl_selesai) > 0) then (select top 7 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-7') between tgl_mulai and tgl_selesai) else [7] end [7]
			, case when [8] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-8') between tgl_mulai and tgl_selesai) > 0) then (select top 8 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-8') between tgl_mulai and tgl_selesai) else [8] end [8]
			, case when [9] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-9') between tgl_mulai and tgl_selesai) > 0) then (select top 9 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-9') between tgl_mulai and tgl_selesai) else [9] end [9]
			, case when [10] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-10') between tgl_mulai and tgl_selesai) > 0) then (select top 10 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-10') between tgl_mulai and tgl_selesai) else [10] end [10]
			, case when [11] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-11') between tgl_mulai and tgl_selesai) > 0) then (select top 11 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-11') between tgl_mulai and tgl_selesai) else [11] end [11]
			, case when [12] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-12') between tgl_mulai and tgl_selesai) > 0) then (select top 12 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-12') between tgl_mulai and tgl_selesai) else [12] end [12]
			, case when [13] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-13') between tgl_mulai and tgl_selesai) > 0) then (select top 13 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-13') between tgl_mulai and tgl_selesai) else [13] end [13]
			, case when [14] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-14') between tgl_mulai and tgl_selesai) > 0) then (select top 14 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-14') between tgl_mulai and tgl_selesai) else [14] end [14]
			, case when [15] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-15') between tgl_mulai and tgl_selesai) > 0) then (select top 15 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-15') between tgl_mulai and tgl_selesai) else [15] end [15]
			, case when [16] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-16') between tgl_mulai and tgl_selesai) > 0) then (select top 16 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-16') between tgl_mulai and tgl_selesai) else [16] end [16]
			, case when [17] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-17') between tgl_mulai and tgl_selesai) > 0) then (select top 17 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-17') between tgl_mulai and tgl_selesai) else [17] end [17]
			, case when [18] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-18') between tgl_mulai and tgl_selesai) > 0) then (select top 18 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-18') between tgl_mulai and tgl_selesai) else [18] end [18]
			, case when [19] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-19') between tgl_mulai and tgl_selesai) > 0) then (select top 19 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-19') between tgl_mulai and tgl_selesai) else [19] end [19]
			, case when [20] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-20') between tgl_mulai and tgl_selesai) > 0) then (select top 20 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-20') between tgl_mulai and tgl_selesai) else [20] end [20]
			, case when [21] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-21') between tgl_mulai and tgl_selesai) > 0) then (select top 21 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-21') between tgl_mulai and tgl_selesai) else [21] end [21]
			, case when [22] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-22') between tgl_mulai and tgl_selesai) > 0) then (select top 22 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-22') between tgl_mulai and tgl_selesai) else [22] end [22]
			, case when [23] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-23') between tgl_mulai and tgl_selesai) > 0) then (select top 23 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-23') between tgl_mulai and tgl_selesai) else [23] end [23]
			, case when [24] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-24') between tgl_mulai and tgl_selesai) > 0) then (select top 24 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-24') between tgl_mulai and tgl_selesai) else [24] end [24]
			, case when [25] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-25') between tgl_mulai and tgl_selesai) > 0) then (select top 25 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-25') between tgl_mulai and tgl_selesai) else [25] end [25]
			, case when [26] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-26') between tgl_mulai and tgl_selesai) > 0) then (select top 26 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-26') between tgl_mulai and tgl_selesai) else [26] end [26]
			, case when [27] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-27') between tgl_mulai and tgl_selesai) > 0) then (select top 27 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-27') between tgl_mulai and tgl_selesai) else [27] end [27]
			, case when [28] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-28') between tgl_mulai and tgl_selesai) > 0) then (select top 28 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-28') between tgl_mulai and tgl_selesai) else [28] end [28]
			, case when isdate(convert(varchar(10), cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-29')) = 1 then case when [1] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-29') between tgl_mulai and tgl_selesai) > 0) then (select top 29 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-29') between tgl_mulai and tgl_selesai) else [29] end else null end [29]
			, case when isdate(convert(varchar(10), cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-29')) = 1 then case when [1] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-30') between tgl_mulai and tgl_selesai) > 0) then (select top 30 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-30') between tgl_mulai and tgl_selesai) else [30] end else null end [30]
			, case when isdate(convert(varchar(10), cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-29')) = 1 then case when [1] is null and ((select count(*) from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-31') between tgl_mulai and tgl_selesai) > 0) then (select top 31 kode_jenis_absen from tp_formulir_headers where a.nik_simkar = nik_simkar and convert(date, cast(tahun as varchar) + '-'+ cast(bulan as varchar) + '-31') between tgl_mulai and tgl_selesai) else [31] end else null end [31]

			from (
			SELECT 
				pin
				, nik_simkar
				, year(tgl) tahun
				, month(tgl) bulan
				, day(tgl) tgls
				, case when check_in is not null or check_out is not null then 'K' end status 
			FROM (
				select pin, nik_simkar, tgl, check_in, check_out from v_absensi_mobile_kanpus union all
				select pin, nik_simkar, tgl, min(ci) check_in , max(co) check_out
				from (
					select 
					pin
					, nik_simkar
					, convert(date, datetime) tgl
					, case when (datename(dw, datetime) = 'Friday') then
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00') > 0) then 
								(select min(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00')
							else null end
						else 
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00') > 0) then 
								(select min(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00')
							else null end
						end
						ci
					, case when (datename(dw, a.datetime) = 'Friday') then
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, datetime)) + ' 09:00:00' then
								machine
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00') > 0) then 
								(select top 1 machine from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00' order by datetime asc)
							else null end
					else 
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00' then
								machine
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00') > 0) then 
								(select top 1 machine from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00' order by datetime asc)
							else null end
					end
					mi
					, case when (datename(dw, datetime) = 'Friday') then
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00') > 0) then 
								(select max(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00')
							else null end
						else 
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 10:30:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00') > 0) then 
								(select max(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00')
							else null end
						end
						co
					, case when (datename(dw, datetime) = 'Friday') then
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00' then
								machine
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00') > 0) then 
								(select top 1 machine from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00' order by datetime desc)
							else null end
						else 
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 10:30:00' then
								machine
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00') > 0) then 
								(select top 1 machine from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00' order by datetime desc)
							else null end
						end
						mo
					from (
						select a.*, p.nik_simkar from tp_att_log_dailies a
						left join tp_pesertas p on p.pin = a.pin
						where p.kodeunitbagian IN ('00', '62')
						and p.satpam = 0 or p.satpam is null
					) a
				) a
				where not exists (select * from tr_libur_cuti_bersamas l where l.tgl = a.tgl)
				group by pin, nik_simkar, tgl

				UNION ALL

				select pin, nik_simkar, tgl, min(ci) check_in , max(co) check_out
				from (
					select 
					pin
					, nik_simkar
					, convert(date, datetime) tgl
					, case when (datename(dw, datetime) in ('Friday', 'Saturday')) then 
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00') > 0) then 
								(select min(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 09:00:00')
							else null end
						else -- when (datename(dw, datetime) in ('Monday', 'Tuesday', 'Wednesday', 'Thursday') then
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00') > 0) then 
								(select min(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 04:00:00' and convert(varchar, convert(date, a.datetime)) + ' 10:30:00')
							else null end
						end
						ci
					, case when (datename(dw, datetime) in ('Friday', 'Saturday')) then 
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00') > 0) then 
								(select min(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 09:00:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00')
							else null end		
						else -- when (datename(dw, datetime) in ('Monday', 'Tuesday', 'Wednesday', 'Thursday') then
							case when datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00' then
								datetime
							when ((select count(*) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00') > 0) then 
								(select min(datetime) from tp_att_log_dailies where pin = a.pin and datetime between convert(varchar, convert(date, a.datetime)) + ' 10:30:01' and convert(varchar, convert(date, dateadd(day, 1, a.datetime))) + ' 03:00:00')
							else null end
						end
						co
					from (
						select a.*, p.nik_simkar from tp_att_log_dailies a
						left join tp_pesertas p on p.pin = a.pin
						where p.kodeunitbagian IN ('01', '02', '03', '04')
						and p.satpam = 0 or p.satpam is null
					) a
				) a
				where not exists (select * from tr_libur_cuti_bersamas l where l.tgl = a.tgl)
				group by pin, nik_simkar, tgl
			) A
			-- where nik_simkar = '006404003'
			union all

			SELECT 
				null pin
				, nik nik_simkar
				, year(tgl) tahun
				, month(tgl) bulan
				, day(tgl) tgls
				, case when ((select count(*) from tp_formulir_headers where a.nik= nik_simkar and tgl between tgl_mulai and tgl_selesai) > 0) then 
						(select top 1 kode_jenis_absen from tp_formulir_headers where a.nik= nik_simkar and tgl between tgl_mulai and tgl_selesai)		
					else 'K' end status
			from (
				select nik, nama, unit, d.tgl from karyawan_ptpn4 k
				join unitbagianmaster u on u.kodeunitbagian = k.unit
				join (select tgl from dbo.fn_get_list_date_range(@s, @d) where datename(dw, tgl) not in ('sunday') and tgl < getdate()) d on 1=1
				where k.status = '01' and u.kelompok not in ('KANPUS', 'GUU', 'KPJ')
				and not exists (select * from tr_libur_cuti_bersamas l where l.tgl = d.tgl)
			) a

			) a
			PIVOT
			(
				max(status)
				FOR tgls IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16],[17],[18],[19],[20],[21],[22],[23],[24],[25],[26],[27],[28],[29],[30],[31])
			) a
			where nik_simkar = @nik and bulan = @bulan and tahun = @tahun
		end
		else begin
			select null
		end
		END;");
    }
}
