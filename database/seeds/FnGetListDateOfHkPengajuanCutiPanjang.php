<?php

use Illuminate\Database\Seeder;

class FnGetListDateOfHkPengajuanCutiPanjang extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_list_date_of_hk_pengajuan_cuti_panjang') IS NOT NULL) DROP FUNCTION fn_get_list_date_of_hk_pengajuan_cuti_panjang;");      
    	DB::statement("CREATE FUNCTION fn_get_list_date_of_hk_pengajuan_cuti_panjang (
    		@nik varchar(15)
    		, @mulai as date
    		, @selesai as date
    	) returns @listDays table (tgl date)
    	as begin
    	insert into @listDays
    		select tgl from dbo.fn_get_list_date_range(@mulai, dateadd(day, 1, @selesai)) a 
    		where not exists (
    		select * from tr_libur_cuti_bersamas b 
    		where b.tgl = a.tgl and is_cb = 1
    		)

    	return;
    	end;");
    }
}
