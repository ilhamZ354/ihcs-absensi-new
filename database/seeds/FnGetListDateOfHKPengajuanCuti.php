<?php

use Illuminate\Database\Seeder;

class FnGetListDateOfHKPengajuanCuti extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_list_date_of_hk_pengajuan_cuti') IS NOT NULL) DROP FUNCTION fn_get_list_date_of_hk_pengajuan_cuti;");      
        DB::statement("CREATE FUNCTION dbo.fn_get_list_date_of_hk_pengajuan_cuti (
			@nik varchar(15)
			, @mulai as date
			, @selesai as date
		)
		RETURNS @listDays table (tgl date)
		AS
		BEGIN
			declare @query varchar(max)
				, @kodeunit varchar(5)
				, @result smallint

			set @query = 'select count(tgl) result from dbo.fn_get_list_date_range('''+convert(varchar, @mulai)+''', '''+convert(varchar, @selesai)+''') a '

			select top 1 @kodeunit=u.kodeunitbagian from v_history_karyawan k
			join unitbagianmaster u on u.namaunitbagian=k.namaunitbagian
			where nik = @nik and tahun = year(@mulai) and bulan = month(@mulai)

			if(@kodeunit in ('00', '62'))
				insert into @listDays
				select tgl from dbo.fn_get_list_date_range(@mulai, dateadd(day, 1, @selesai)) a 
				where DATENAME(dw, a.tgl) not in ('Saturday', 'Sunday')
				and not exists (
					select * from tr_libur_cuti_bersamas b 
					where b.tgl = a.tgl
				)
			else 
				insert into @listDays
				select tgl from dbo.fn_get_list_date_range(@mulai, dateadd(day, 1, @selesai)) a 
				where DATENAME(dw, a.tgl) not in ('Sunday')
				and not exists (
					select * from tr_libur_cuti_bersamas b 
					where b.tgl = a.tgl
				)

			return;
		END;");
    }
}
