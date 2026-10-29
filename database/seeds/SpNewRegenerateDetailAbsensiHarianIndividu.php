<?php

use Illuminate\Database\Seeder;

class SpNewRegenerateDetailAbsensiHarianIndividu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_new_regenerate_detail_absensi_harian_individu') IS NOT NULL) DROP PROCEDURE sp_new_regenerate_detail_absensi_harian_individu");
        DB::statement("CREATE PROCEDURE sp_new_regenerate_detail_absensi_harian_individu
			@nik varchar(15)
			, @s date
			, @e date
			, @created_by varchar(50)
		AS
		BEGIN

			declare @tbl as table( tgl date )
			insert @tbl (tgl)
			select tgl from dbo.fn_get_list_date_range(@s, dateadd(day, 1, @e))

			while exists(select * from @tbl) begin
				declare @dt date
				select top 1 @dt = tgl from @tbl order by tgl asc

				exec sp_new_generate_detail_absensi_harian_individu @dt, @nik, @created_by

				exec sp_new_generate_detail_absensi_harian_individu_nonmesin @dt, @nik, @created_by

				delete @tbl where tgl = @dt
			end 

		END;");
    }
}
