<?php

use Illuminate\Database\Seeder;

class FnGetCutiPanjang extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_get_cuti_panjang') IS NOT NULL) DROP FUNCTION fn_get_cuti_panjang;");
        DB::statement("CREATE FUNCTION [dbo].[fn_get_cuti_panjang] (
						@pNikSimkar varchar(15),
						@pTahun smallint,
						@pBulan smallint
					)
					RETURNS SMALLINT
					AS 
					BEGIN

					DECLARE @tglBekerja datetime
					select top 1 @tglBekerja = TANGGAL_BEKERJA from karyawan_ptpn4 where status = '01' and nik = @pNikSimkar
					return (
						CASE WHEN (DATEDIFF(MONTH, @tglBekerja, cast(cast(@pTahun as varchar(4))+'-'+(cast(@pBulan as varchar(2)))+'-01' as datetime)) % 60) < 12 THEN
						45
						END);
					END;");
    }
}
