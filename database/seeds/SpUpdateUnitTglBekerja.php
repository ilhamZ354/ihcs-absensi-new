<?php

use Illuminate\Database\Seeder;

class SpUpdateUnitTglBekerja extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_update_unit_tgl_bekerja') IS NOT NULL) DROP PROCEDURE sp_update_unit_tgl_bekerja");
    	DB::statement("CREATE PROCEDURE [dbo].[sp_update_unit_tgl_bekerja]
		AS
		BEGIN
			update p set 
				p.kodeunitbagian = simkar.unit
				, p.nama_mesin = simkar.nama
				,  p.tgl_bekerja = simkar.tanggal_bekerja 
				, p.updated_by = 'Harapan'
				, p.updated_at = getdate()
			from tp_pesertas p
			join (
			SELECT * FROM (
				SELECT NIK, NAMA, UNIT, CAST(TANGGAL_BEKERJA AS DATE) TANGGAL_BEKERJA FROM KARYAWAN_PTPN4
				WHERE NIK IN (
					SELECT nik_simkar FROM [dbo].[tp_pesertas]
				)
				UNION
				SELECT NIK, NAMA_KARYAWAN AS NAMA, UNIT, CAST(TGL_BEKERJA AS DATE) TANGGAL_BEKERJA FROM TM_KARYAWAN_NONPTPN4
				WHERE NIK IN (
					SELECT nik_simkar FROM [dbo].[tp_pesertas]
				)
			)A
			) simkar on simkar.nik = p.nik_simkar
		END;");
    }
}
