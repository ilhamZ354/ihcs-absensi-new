<?php

use Illuminate\Database\Seeder;

class FnGetJmlHkPengajuanCutiPanjang extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_jml_hk_pengajuan_cuti_panjang') IS NOT NULL) DROP FUNCTION fn_get_jml_hk_pengajuan_cuti_panjang;");      
    	DB::statement("CREATE FUNCTION fn_get_jml_hk_pengajuan_cuti_panjang (
    		@nik varchar(15)
    		, @mulai as date
    		, @selesai as date
    	) returns smallint
    	as begin
    	declare @result smallint

    	select @result = count(tgl) from dbo.fn_get_list_date_range(@mulai, dateadd(day, 1, @selesai)) a 
    	where not exists (
    	select * from tr_libur_cuti_bersamas b 
    	where b.tgl = a.tgl and is_cb = 1
    	)

    	return @result
    	end;");
    }
}
