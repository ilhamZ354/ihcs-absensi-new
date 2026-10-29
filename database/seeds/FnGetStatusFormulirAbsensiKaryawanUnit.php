<?php

use Illuminate\Database\Seeder;

class FnGetStatusFormulirAbsensiKaryawanUnit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_status_formulir_absensi_karyawan_unit') IS NOT NULL) DROP FUNCTION fn_get_status_formulir_absensi_karyawan_unit;");
        DB::statement("CREATE FUNCTION fn_get_status_formulir_absensi_karyawan_unit (@tgl as date, @nik as varchar(15))
		RETURNS varchar(5)
		AS 
		BEGIN
			return (select h.kode_jenis_absen
			from tp_formulir_headers h
            where h.nik_simkar = @nik
			and @tgl between h.tgl_mulai and h.tgl_selesai
			and status = 1)
		END;");
    }
}
