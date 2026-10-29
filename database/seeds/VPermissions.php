<?php

use Illuminate\Database\Seeder;

use App\Models\TrPermission;

class VPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/*TrPermission::insert([
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'kode-absensi',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'kode-absensi',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'permission',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'permission',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'permission',	
    			'action' => 'create',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'libur-cuti-bersama',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'libur-cuti-bersama',	
    			'action' => 'create',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'libur-cuti-bersama',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'peserta',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'peserta',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'data-kehadiran',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'data-kehadiran',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'formulir-absensi',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'ketidakhadiran',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'formulir-absensi',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'data-kehadiran',	
    			'action' => 'create',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'formulir-absensi',	
    			'action' => 'create',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_BAGIAN_ABSENSI',
    			'slug' => 'formulir-absensi',	
    			'action' => 'delete',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'formulir-absensi',	
    			'action' => 'delete',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'formulir-absensi',	
    			'action' => 'create',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'ketidakhadiran',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'transaksi-absensi',	
    			'action' => 'show',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'transaksi-absensi',	
    			'action' => 'delete',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'transaksi-absensi',	
    			'action' => 'edit',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    		[
    			'kode_group' => 'ADMIN_ABSENSI',
    			'slug' => 'ketidakhadiran',	
    			'action' => 'create',
    			'created_by' => 'System',
    			'created_at' => now()
    		],
    	]);*/


    	DB::statement("IF (OBJECT_ID('v_permissions') IS NOT NULL) DROP VIEW v_permissions");
        DB::statement("CREATE VIEW v_permissions
		AS
    	SELECT
            *
        FROM
            (
            select g.kode [kode_group], r.slug, r.slug [role], rp.permission
            from dev_sso.dbo.tr_groups g
            join tp_roles r on '1'='1'
            left join tp_role_permissions rp on rp.kode_group = g.kode and rp.slug = r.slug
            where g.id_application = 2
        ) a PIVOT (
            COUNT (role) FOR [permission] IN ([show],[create],[store],[edit],[update],[execute],[delete])
        ) p;");
    }
}
