<?php

use Illuminate\Database\Seeder;

class FnGetListHariKerjaByIdPeserta extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_get_list_hari_kerja_by_id_peserta') IS NOT NULL) DROP FUNCTION fn_get_list_hari_kerja_by_id_peserta;");
        DB::statement("CREATE FUNCTION fn_get_list_hari_kerja_by_id_peserta
			( @id_peserta AS int )
			RETURNS @listDays TABLE (
				nama_hari	varchar(50),
				english_name	varchar(50)
			)
			AS
			BEGIN
				insert into @listDays
				select distinct ih.nama_hari, ih.english_name from tp_pesertas p
				left join tm_unit_kelompok_kerjas ukk on ukk.kodeunitbagian = p.kodeunitbagian
				join tr_kelompok_kerjas kk on kk.id = ukk.id_kelompok_kerja
				join tr_kelompok_kerja_details kd on kd.id_kelompok_kerja = kk.id
				join tr_kelompok_kerja_harians kh on kh.id_kelompok_kerja_detail = kd.id
				join tr_index_nama_haris ih on ih.nama_hari = kh.nama_hari
				where p.id = @id_peserta and (p.satpam = ukk.satpam or ukk.satpam is null);
			  RETURN
			END;");
    }
}
