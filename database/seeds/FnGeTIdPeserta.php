<?php

use Illuminate\Database\Seeder;

class FnGeTIdPeserta extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_get_id_peserta') IS NOT NULL) DROP FUNCTION fn_get_id_peserta;");      
        DB::statement("CREATE FUNCTION [dbo].[fn_get_id_peserta] (@pin int)
        RETURNS int
        AS
        BEGIN
            return (select id from tp_pesertas where pin = @pin);
        END;");


        DB::statement("IF (OBJECT_ID('fn_get_id_peserta_by_nik_simkar') IS NOT NULL) DROP FUNCTION fn_get_id_peserta_by_nik_simkar;");    	
        DB::statement("CREATE FUNCTION [dbo].[fn_get_id_peserta_by_nik_simkar] (@nik_simkar varchar(15))
        RETURNS int
        AS
        BEGIN
            return (select id from tp_pesertas where nik_simkar = @nik_simkar);
        END;");
    }
}
