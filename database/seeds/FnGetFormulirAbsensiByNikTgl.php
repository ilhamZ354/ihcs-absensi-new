<?php

use Illuminate\Database\Seeder;

class FnGetFormulirAbsensiByNikTgl extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_get_formulir_absensi_by_nik_tgl') IS NOT NULL) DROP FUNCTION fn_get_formulir_absensi_by_nik_tgl;");
        DB::statement("CREATE FUNCTION [dbo].[fn_get_formulir_absensi_by_nik_tgl] ( 
			@nik_simkar varchar(15),
			@tgl date
		)
		RETURNS VARCHAR(5)
		AS
		BEGIN
			RETURN (select kode_jenis_absen from tp_formulir_headers 
			where nik_simkar = @nik_simkar
			and (@tgl between tgl_mulai and tgl_selesai)
			and status = 1)
		END;");
    }
}
