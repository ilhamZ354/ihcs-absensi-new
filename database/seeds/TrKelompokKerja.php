<?php

use Illuminate\Database\Seeder;

class TrKelompokKerja extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tr_kelompok_kerjas')->insert([
        	[
        		'nama'=> 'KANDIR REGULAR',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama'=> 'KANDIR SATPAM',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama'=> 'GUU REGULER',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        	[
        		'nama'=> 'GUU SATPAM',
        		'created_by' => 'System',
        		'created_at' => date('Y-m-d H:i:s')
        	],
        ]);
    }
}
