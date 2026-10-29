<?php

use Illuminate\Database\Seeder;

class TmUnitKelompokKerja extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tm_unit_kelompok_kerjas')->insert([
        	[	
        		'kodeunitbagian' => '00',
        		'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('KANDIR REGULAR'),
        		'satpam' => false,
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[	
        		'kodeunitbagian' => '00',
        		'id_kelompok_kerja' => get_id_kelompok_kerja_by_name('KANDIR SATPAM'),
        		'satpam' => true,
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	]
        ]);
    }
}
