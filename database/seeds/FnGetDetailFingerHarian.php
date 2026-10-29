<?php

use Illuminate\Database\Seeder;

class FnGetDetailFingerHarian extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_detail_finger_harian') IS NOT NULL) DROP FUNCTION fn_get_detail_finger_harian;");      
        DB::statement("CREATE FUNCTION [dbo].[fn_get_detail_finger_harian] (
			@pin smallint
			, @tgl date
		) returns @detail table (pin int, tgl date, check_in datetime, check_out datetime)
		AS BEGIN

			if((SELECT count(*) FROM tr_absensi_details where tgl = @tgl and pin = @pin and jam_masuk is not null) > 0) begin 
					insert into @detail
					SELECT pin, tgl
					, cast(cast(tgl as varchar(10)) + ' ' + cast(checkin_time as varchar(12)) as datetime) check_in
					, cast(cast(tgl as varchar(10)) + ' ' + cast(checkout_time as varchar(12)) as datetime) check_out
					FROM tr_absensi_details where tgl = @tgl and pin = @pin
			end else begin
				insert into @detail
				select pin, tgl, min(check_in) check_in, max(check_out) check_out from (
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
							and (p.satpam = 0 or p.satpam is null)
							and a.pin = @pin
							and cast(a.datetime as date) = @tgl
						) a
					) a
					group by pin, tgl

					-- ------------------------------------------------------
					-- START UNION WITH ABSENSI MOBILE
					-- ------------------------------------------------------

					union all

					select pin, tgl, check_in , check_out from v_absensi_mobile_kanpus m where pin = @pin and tgl = @tgl

					-- ------------------------------------------------------
					-- END UNION WITH ABSENSI MOBILE
					-- ------------------------------------------------------
				) a group by pin, tgl
				
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
						and (p.satpam = 0 or p.satpam is null)
						and a.pin = @pin
						and cast(a.datetime as date) = @tgl
					) a
				) a			
				group by pin, tgl
			end
			
			return;
		END;");
    }
}
