<?php

use Illuminate\Database\Seeder;

class SpGetListAbsensiRedundant extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_get_list_absensi_redundant') IS NOT NULL) DROP PROCEDURE sp_get_list_absensi_redundant");
        DB::statement("CREATE PROCEDURE sp_get_list_absensi_redundant
			@tahun smallint,
			@bulan smallint
		AS 

		BEGIN
			select * from tp_pesertas p 
			where exists (
				select * from (
					select nik_simkar, tgl from tr_absensi_details 
					where year(tgl) = @tahun and month(tgl) = @bulan
					group by nik_simkar, tgl
					having count(tgl) > 1
				)a
				where a.nik_simkar = p.nik_simkar
			)
		END;");
    }
}
