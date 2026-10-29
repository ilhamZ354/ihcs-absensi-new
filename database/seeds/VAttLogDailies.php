<?php

use Illuminate\Database\Seeder;

class VAttLogDailies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_att_log_dailies') IS NOT NULL) DROP PROCEDURE v_att_log_dailies");    	
        DB::statement("CREATE VIEW v_att_log_dailies 
        	AS
        	select 
			isnull(k.nama, kh.nama) nama
			, isnull(k.namaunitbagian, kh.namaunitbagian) namaunitbagian
			, isnull(k.namabagian, kh.namabagian) namabagian
			, year(a.tgl) tahun 
			, month(a.tgl) bulan 
			, a.* 
			from (
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
			    and (p.satpam = 0 or p.satpam is null)
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
			    and (p.satpam = 0 or p.satpam is null) 
			  ) a
			) a
			where not exists (select * from tr_libur_cuti_bersamas l where l.tgl = a.tgl)
			group by pin, nik_simkar, tgl
			) a
			left join (select k.nik, k.nama, u.namaunitbagian, b.namabagian from karyawan_ptpn4 k
			  left join (select kodeunitbagian, namaunitbagian from unitbagianmaster) u on u.kodeunitbagian = k.unit
			  left join tm_bagian b on b.kodebagian = k.kodebagian
			) k on k.nik = a.nik_simkar
			left join (select k.nik, k.nama_karyawan nama, u.namaunitbagian, b.namabagian from tm_karyawan_nonptpn4 k
			  left join (select kodeunitbagian, namaunitbagian from unitbagianmaster) u on u.kodeunitbagian = k.unit
			  left join tm_bagian b on b.kodebagian = k.kodebagian
			) kh on kh.nik = a.nik_simkar");
    }
}
