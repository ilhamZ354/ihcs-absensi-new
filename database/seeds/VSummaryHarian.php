<?php

use Illuminate\Database\Seeder;

class VSummaryHarian extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_summary_harian_dari_mesin') IS NOT NULL) DROP VIEW v_summary_harian_dari_mesin");
        DB::statement("CREATE VIEW v_summary_harian_dari_mesin
		AS
		select * from (
		SELECT pin, year(tgl) tahun, month(tgl) bulan, day(tgl) tgls, case when check_in is not null or check_out is not null then 'K' else null end status FROM (
			select pin, tgl, min(ci) check_in , max(co) check_out
			from (
				select 
				pin
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
					select a.* from tp_att_log_dailies a
					left join tp_pesertas p on p.pin = a.pin
					where p.kodeunitbagian IN ('00', '62')
					and p.satpam = 0 or p.satpam is null
				) a
			) a
			group by pin, tgl

			UNION ALL

			select pin, tgl, min(ci) check_in , max(co) check_out
			from (
				select 
				pin
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
					select a.* from tp_att_log_dailies a
					left join tp_pesertas p on p.pin = a.pin
					where p.kodeunitbagian IN ('01', '02', '03', '04')
					and p.satpam = 0 or p.satpam is null
				) a
			) a
			group by pin, tgl
		) A
		) a
		PIVOT
		(
			max(status)
			FOR tgls IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16],[17],[18],[19],[20],[21],[22],[23],[24],[25],[26],[27],[28],[29],[30],[31])
		) P;");
    }
}
