<?php

use Illuminate\Database\Seeder;

class SpUpdateSatpamFromSimkar extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_update_satpam_from_simkar') IS NOT NULL) DROP PROCEDURE sp_update_satpam_from_simkar");
        DB::statement("CREATE PROCEDURE sp_update_satpam_from_simkar
		AS
		BEGIN
			update p set p.satpam = 1 from tp_pesertas p 
			where exists (
				select nik, nama from karyawan_ptpn4 k 
				where status = '01'
				and kodejabatan in (
					select kodejabatan from tm_jabatan where jabatan like '%satpam%' and isactive = 'Y'
				)
				and p.nik_simkar = k.nik
			);
			
			update p set p.satpam = 0 from tp_pesertas p 
			where not exists (
				select nik, nama from karyawan_ptpn4 k 
				where status = '01'
				and kodejabatan in (
					select kodejabatan from tm_jabatan where jabatan like '%satpam%' and isactive = 'Y'
				)
				and p.nik_simkar = k.nik
			)
		END;");
    }
}
