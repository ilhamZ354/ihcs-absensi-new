<?php

use Illuminate\Database\Seeder;

class TrIndexNamaHari extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tr_index_nama_haris')->insert([
        	[
        		'nama_hari' => 'SENIN',
        		'english_name' => 'MONDAY',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama_hari' => 'SELASA',
        		'english_name' => 'TUESDAY',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama_hari' => 'RABU',
        		'english_name' => 'WEDNESDAY',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama_hari' => 'KAMIS',
        		'english_name' => 'THURSDAY',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama_hari' => 'JUMAT',
        		'english_name' => 'FRIDAY',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama_hari' => 'SABTU',
        		'english_name' => 'SATURDAY',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama_hari' => 'MINGGU',
        		'english_name' => 'SUNDAY',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	]
        ]);
    }
}
