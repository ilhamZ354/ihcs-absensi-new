<?php

use Illuminate\Database\Seeder;

class TrLiburCutiBersama extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tr_libur_cuti_bersamas')->insert([
        	[
                'tgl' => '2018-11-20',
                'uraian' => 'Maulid Nabi Muhammad SAW',
                'status' => 1,
                'is_cb' => 0,
                'created_by' => 'System',
                'created_at' => now()
            ],
            [
                'tgl' => '2018-12-25',
                'uraian' => 'Hari Raya Natal',
                'status' => 1,
                'is_cb' => 0,
                'created_by' => 'System',
                'created_at' => now()
            ],
            [
        		'tgl' => '2018-12-24',
        		'uraian' => 'Cuti Bersama Hari Raya Natal',
                'status' => 1,
        		'is_cb' => 1,
        		'created_by' => 'System',
        		'created_at' => now()
        	]
        ]);
    }
}
