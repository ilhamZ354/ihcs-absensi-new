<?php

use Illuminate\Database\Seeder;

class FnIsLiburAtauCutiBersama extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_is_libur_atau_cuti_bersama') IS NOT NULL) DROP FUNCTION fn_is_libur_atau_cuti_bersama;");
        DB::statement("CREATE FUNCTION [dbo].[fn_is_libur_atau_cuti_bersama] ( 
			@tgl AS date
		)
		RETURNS smallint
		AS
		BEGIN
			RETURN (select count(*) from tr_libur_cuti_bersamas
			where tgl = @tgl
			and status = 1)
		END;");
    }
}
