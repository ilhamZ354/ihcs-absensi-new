<?php

use Illuminate\Database\Seeder;

class SpInsertOrRewritePeserta extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_insert_or_overwrite_peserta') IS NOT NULL) DROP PROCEDURE sp_insert_or_overwrite_peserta");
        DB::statement("CREATE PROCEDURE sp_insert_or_overwrite_peserta
			AS
			BEGIN
				insert into tp_pesertas (nik_simkar, pin ,nama_mesin ,status ,created_by, created_at)
				select nik_simkar, pin ,nama_mesin ,status ,created_by, getdate() [created_at]
				 from tmp_pesertas a where not exists(
					select * from tp_pesertas b
					where b.nik_simkar = a.nik_simkar
					and b.pin = a.pin
				)

				truncate table tmp_pesertas;
				delete tp_pesertas where nik_simkar is null or nik_simkar = '';
				delete tp_pesertas where substring(nama_mesin, 1, 3) = 'OLD';
				exec sp_update_unit_tgl_bekerja;
				exec sp_update_satpam_from_simkar;

			END;");
    }
}
