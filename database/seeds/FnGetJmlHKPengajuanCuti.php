<?php

use Illuminate\Database\Seeder;

class FnGetJmlHKPengajuanCuti extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_get_jml_hk_pengajuan_cuti') IS NOT NULL) DROP FUNCTION fn_get_jml_hk_pengajuan_cuti;");      
        DB::statement("CREATE FUNCTION [dbo].[fn_get_jml_hk_pengajuan_cuti] (
			@nik varchar(15)
			, @mulai as date
			, @selesai as date
		)
		RETURNS smallint
		AS
		BEGIN
			declare @query varchar(max)
				, @kodeunit varchar(5)
				, @result smallint

			set @query = 'select count(tgl) result from dbo.fn_get_list_date_range('''+convert(varchar, @mulai)+''', '''+convert(varchar, @selesai)+''') a '

			--if((select count(*) from v_history_karyawan where tahun = year(@mulai) and bulan = month(@mulai)) > 0)
				select top 1 @kodeunit=u.kodeunitbagian from v_history_karyawan k 
				join unitbagianmaster u on u.namaunitbagian=k.namaunitbagian
				where nik = @nik and tahun = year(@mulai) and bulan = month(@mulai)
			-- else 
			-- 	select top 1 @kodeunit=k.unit from karyawan_ptpn4 k where k.nik = @nik

			if(@kodeunit in ('00', '62'))
				select @result = count(tgl) from dbo.fn_get_list_date_range(@mulai, dateadd(day, 1, @selesai)) a 
				where DATENAME(dw, a.tgl) not in ('Saturday', 'Sunday')
				and not exists (
					select * from tr_libur_cuti_bersamas b 
					where b.tgl = a.tgl
				)
			else 
				select @result = count(tgl) from dbo.fn_get_list_date_range(@mulai, dateadd(day, 1, @selesai)) a 
				where DATENAME(dw, a.tgl) not in ('Sunday')
				and not exists (
					select * from tr_libur_cuti_bersamas b 
					where b.tgl = a.tgl
				)

			return @result
		END;");
    }
}
