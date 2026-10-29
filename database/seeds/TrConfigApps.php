<?php

use Illuminate\Database\Seeder;
use App\Models\TrConfigApp;

class TrConfigApps extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TrConfigApp::insert([
        	'key' => 'path_mdb',
        	'value' => 'E:\absensi\ATT2000\ATT2000.MDB',
        	'status' => '1',
        	'created_by' => 'System',
        	'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
