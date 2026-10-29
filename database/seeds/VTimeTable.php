<?php

use Illuminate\Database\Seeder;

class VTimeTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_timetable') IS NOT NULL) DROP VIEW v_timetable");
    	DB::statement("CREATE VIEW v_timetable
    		AS
    		select a.id, a.nama, b.nama [nama_shift], c.nama_hari, d.english_name, b.jam_masuk, b.jam_masuk_min, b.jam_masuk_max, b.jam_pulang, b.jam_pulang_min, b.jam_pulang_max from tr_kelompok_kerjas a
    		join tr_kelompok_kerja_details b on b.id_kelompok_kerja = a.id
    		join tr_kelompok_kerja_harians c on c.id_kelompok_kerja_detail = b.id
    		join tr_index_nama_haris d on d.nama_hari = c.nama_hari;");
    }
}
