<?php

use Illuminate\Database\Seeder;

class FnGetDurasiDalamMenit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_durasi_dalam_menit') IS NOT NULL) DROP FUNCTION fn_get_durasi_dalam_menit;");
        DB::statement("CREATE FUNCTION [dbo].[fn_get_durasi_dalam_menit]
		( 
			@menit AS int 
		)
		RETURNS time
		AS
		BEGIN  
			declare @jam as int
			select @jam = @menit / 60, @menit = @menit % 60
			return (select cast(@jam as varchar(5)) + ':'+ cast(@menit as varchar(2)))
		  
		END;");
    }
}
