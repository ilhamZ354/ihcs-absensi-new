<?php

use Illuminate\Database\Seeder;

class VListUserIHCS extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_list_user_ihcs') IS NOT NULL) DROP VIEW v_list_user_ihcs");
        DB::statement("CREATE VIEW v_list_user_ihcs
        	AS
        	select id_user, nama, username from dev_sso.dbo.tr_users;");
    }
}
