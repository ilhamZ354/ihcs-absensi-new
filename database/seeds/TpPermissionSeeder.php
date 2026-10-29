<?php

use Illuminate\Database\Seeder;
use App\Models\TpPermission;

class TpPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	TpPermission::insert([
    		[
    			'kode' => 'show',
    			'name' => 'READ/VIEW',
    			'row_status' => 1,
    			'created_by' => 'System',
    			'updated_at' => now(),
    		],
    		[
    			'kode' => 'create',
    			'name' => 'CREATE',
    			'row_status' => 1,
    			'created_by' => 'System',
    			'updated_at' => now(),
    		],
    		[
    			'kode' => 'store',
    			'name' => 'STORE',
    			'row_status' => 1,
    			'created_by' => 'System',
    			'updated_at' => now(),
    		],
    		[
    			'kode' => 'edit',
    			'name' => 'EDIT',
    			'row_status' => 1,
    			'created_by' => 'System',
    			'updated_at' => now(),
    		],
    		[
    			'kode' => 'update',
    			'name' => 'UPDATE',
    			'row_status' => 1,
    			'created_by' => 'System',
    			'updated_at' => now(),
    		],
    		[
    			'kode' => 'execute',
    			'name' => 'EXECUTE',
    			'row_status' => 1,
    			'created_by' => 'System',
    			'updated_at' => now(),
    		],
    		[
    			'kode' => 'delete',
    			'name' => 'DELETE',
    			'row_status' => 1,
    			'created_by' => 'System',
    			'updated_at' => now(),
    		],
    	]);
    }
}
