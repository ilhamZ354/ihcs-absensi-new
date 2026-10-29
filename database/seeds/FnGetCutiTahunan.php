<?php

use Illuminate\Database\Seeder;

class FnGetCutiTahunan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_get_cuti_tahunan') IS NOT NULL) DROP FUNCTION fn_get_cuti_tahunan;");
        DB::statement("CREATE FUNCTION [dbo].[fn_get_cuti_tahunan] (
						@pNikSimkar VARCHAR(15),
						@pTahun SMALLINT,
						@pBulan SMALLINT
					)
					RETURNS SMALLINT
					AS 
					BEGIN

					DECLARE @tglBekerja DATETIME
					select top 1 @tglBekerja = TANGGAL_BEKERJA from karyawan_ptpn4 where status = '01' and nik = @pNikSimkar
					return (
						CASE WHEN (DATEDIFF(MONTH, @tglBekerja, cast(cast(@pTahun as VARCHAR(4))+'-'+(cast(@pBulan as VARCHAR(2)))+'-01' as DATETIME)) % 12) = 0 THEN
						12
						END);
					END;");
    }
}
