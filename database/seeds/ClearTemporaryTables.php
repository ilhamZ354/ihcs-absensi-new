<?php

use Illuminate\Database\Seeder;

class ClearTemporaryTables extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('tmp_absensi_details') IS NOT NULL) truncate table tmp_absensi_details;");
        DB::statement("IF (OBJECT_ID('tmp_pesertas') IS NOT NULL) truncate table tmp_pesertas;");
        DB::statement("exec sp_generate_all_kelompok_kerja_harian;");
    }
}
