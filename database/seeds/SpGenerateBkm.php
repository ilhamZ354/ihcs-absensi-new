<?php

use Illuminate\Database\Seeder;

class SpGenerateBkm extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_bkm') IS NOT NULL) DROP PROCEDURE sp_generate_bkm");
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_bkm]
			@unit varchar(5) -- = '32'
			, @tahun smallint -- = 2019
			, @bulan smallint -- = 2
		AS
		BEGIN

			DECLARE
			@companyCode varchar(4) = 'N00D'

			select
			@companyCode Company_Code
			, d.tgl Tanggal
			, k.nik_sap NIK_Employee_ID
			, k.nama NAMA_KARYAWAN
			, k.namabagian
			, case 
					when f.kode_jenis_absen is null and ((datename(dw, d.tgl) = 'Sunday') or (l.tgl is not null )) then 'L'
					when f.kode_jenis_absen is null and (datename(dw, d.tgl) <> 'Sunday') then 'K' 
					when f.kode_jenis_absen = 'K' and (datename(dw, d.tgl) = 'Sunday') then 'KM'
					when f.kode_jenis_absen = 'K' and (l.tgl = d.tgl) and (datename(dw, d.tgl) <> 'Sunday') then 'KL'
					when f.kode_jenis_absen is not null and f.kode_jenis_absen not in('K') then f.kode_jenis_absen
					else null 
				end
					Kehadiran_Attendance

		-- 	, case when f.kode_jenis_absen is null then d.tgl else null end Clock_In_Date
		-- 	, case when f.kode_jenis_absen is null then '06:30:00' else null end Clock_In_Time
		-- 	, case when f.kode_jenis_absen is null then d.tgl else null end Clock_Out_Date
		-- 	, case when f.kode_jenis_absen is null then '15:00:00' else null end Clock_Out_Time

			, d.tgl Clock_In_Date
			, '06:30:00' Clock_In_Time
			, d.tgl Clock_Out_Date
		-- 	, '15:00:00' Clock_Out_Time
			, case when datename(dw, d.tgl) = 'Friday' then '12:00:00' 
						 when datename(dw, d.tgl) = 'Saturday' then '13:00:00' 
						 else '15:00:00'
				end Clock_Out_Time

			, 1 [NOL]
			, 'HK' [Mandays_UoM]
			from v_history_karyawan k
			join dbo.fn_get_list_date_range(cast((cast(@tahun as varchar(4)) + '-' + cast(@bulan as varchar(2)) + '-01') as date)
				, dateadd(MONTH, 1, cast((cast(@tahun as varchar(4)) + '-' + cast(@bulan as varchar(2)) + '-01') as date))
			) d on 1=1
			left join tp_formulir_headers f on d.tgl between f.tgl_mulai and f.tgl_selesai and f.nik_simkar = k.nik
			left join	tr_libur_cuti_bersamas l on l.tgl = d.tgl

			where k.namaunitbagian = (select namaunitbagian from unitbagianmaster where kodeunitbagian = @unit)
			and k.karpim = 0
			and k.tahun = @tahun
			and k.bulan = @bulan
			order by d.tgl, k.nama

		END;");
    }
}
