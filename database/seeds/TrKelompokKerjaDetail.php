<?php

use Illuminate\Database\Seeder;

class TrKelompokKerjaDetail extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tr_kelompok_kerja_details')->insert([
        	[
        		'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('KANDIR REGULAR'),
        		'nama' => 'KANDIR REG. SENIN-KAMIS',
        		'rentang_hari' => 'SENIN-KAMIS',
        		'jam_masuk' => '08:00',
        		'jam_pulang' => '17:30',
        		'jam_masuk_min' => '04:00',
        		'jam_masuk_max' => '10:30',
        		'jam_pulang_min' => '10:31',
        		'jam_pulang_max' => '03:00',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('KANDIR REGULAR'),
        		'nama' => 'KANDIR REG. JUMAT',
        		'rentang_hari' => 'JUMAT',
        		'jam_masuk' => '08:00',
        		'jam_pulang' => '12:00',
        		'jam_masuk_min' => '04:00',
        		'jam_masuk_max' => '09:00',
        		'jam_pulang_min' => '09:01',
        		'jam_pulang_max' => '03:00',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('KANDIR SATPAM'),
        		'nama' => 'PAGI',
        		'rentang_hari' => 'SENIN-MINGGU',
        		'jam_masuk' => '07:00',
        		'jam_pulang' => '19:00',
        		'jam_masuk_min' => '06:00',
        		'jam_masuk_max' => '12:00',
        		'jam_pulang_min' => '12:01',
        		'jam_pulang_max' => '22:59',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
                'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('KANDIR SATPAM'),
                'nama' => 'MALAM',
                'rentang_hari' => 'SENIN-MINGGU',
                'jam_masuk' => '19:00',
                'jam_pulang' => '07:00',
                'jam_masuk_min' => '18:00',
                'jam_masuk_max' => '23:59',
                'jam_pulang_min' => '00:01',
                'jam_pulang_max' => '12:00',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('GUU REGULER'),
                'nama' => 'GUU REG. SENIN-KAMIS',
                'rentang_hari' => 'SENIN-KAMIS',
                'jam_masuk' => '07:00',
                'jam_pulang' => '15:00',
                'jam_masuk_min' => '04:00',
                'jam_masuk_max' => '10:30',
                'jam_pulang_min' => '10:31',
                'jam_pulang_max' => '03:00',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('GUU REGULER'),
                'nama' => 'GUU REG. JUMAT',
                'rentang_hari' => 'JUMAT',
                'jam_masuk' => '07:00',
                'jam_pulang' => '12:00',
                'jam_masuk_min' => '04:00',
                'jam_masuk_max' => '09:00',
                'jam_pulang_min' => '09:01',
                'jam_pulang_max' => '03:00',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
        		'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('GUU REGULER'),
        		'nama' => 'GUU REG. SABTU',
        		'rentang_hari' => 'SABTU',
        		'jam_masuk' => '07:00',
        		'jam_pulang' => '13:00',
        		'jam_masuk_min' => '04:00',
        		'jam_masuk_max' => '09:00',
        		'jam_pulang_min' => '09:01',
        		'jam_pulang_max' => '03:00',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        ]);
    }
}
