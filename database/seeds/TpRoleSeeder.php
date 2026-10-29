<?php

use Illuminate\Database\Seeder;
use App\Models\TpRole;

class TpRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	TpRole::insert([
    		[
    			'name' => 'Kode absensi',
    			'slug' => 'kode-absensi',
    			'status' => 1,
    			'created_by' => 'System',
    			'created_at' => now(),
    		],
    		[
    			'name' => 'Permission'	,
    			'slug' => 'permission',
    			'status' => 1,
    			'created_by' => 'System',
    			'created_at' => now(),
    		],
    		[
    			'name' => 'Libur & cuti bersama'	,
    			'slug' => 'libur-cuti-bersama',
    			'status' => 1,
    			'created_by' => 'System',
    			'created_at' => now(),
    		],
    		[
    			'name' => 'Data peserta'	,
    			'slug' => 'peserta',
    			'status' => 1,
    			'created_by' => 'System',
    			'created_at' => now(),
    		],
    		[
    			'name' => 'Data kehadiran',
    			'slug' => 'data-kehadiran',
    			'status' => 1,
    			'created_by' => 'System',
    			'created_at' => now(),
    		],
    		[
    			'name' => 'Formulir absensi'	,
    			'slug' => 'formulir-absensi',
    			'status' => 1,
    			'created_by' => 'System',
    			'created_at' => now(),
    		],
    		[
    			'name' => 'Transaksi Absensi'	,
    			'slug' => 'transaksi-absensi',
    			'status' => 1,
    			'created_by' => 'System',
    			'created_at' => now(),
    		]
    	]);
    }
}
