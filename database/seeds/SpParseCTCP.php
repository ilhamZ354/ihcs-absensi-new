<?php

use Illuminate\Database\Seeder;

class SpParseCTCP extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('SP_PARSE_CTCP') IS NOT NULL) DROP PROCEDURE SP_PARSE_CTCP");
        DB::statement("ALTER PROCEDURE [dbo].[SP_PARSE_CTCP]
		@USERNAME VARCHAR(50)
		AS 
		BEGIN
			if((((select count(*) from temp_file_parses where kuota_cuti_tahunan > 12) > 0)) 
			or((select count(*) from temp_file_parses where kuota_cuti_panjang > 45) > 0))
			begin
				-- gagalkan proses
				-- dan kosongkan table temporary
				truncate table temp_file_parses;
				select 'OVERFLOW' [error]
			end
			else begin
				update ct 
					set terpakai = abs(12 - temp.kuota_cuti_tahunan) --ABS(kuota_cuti_tahunan - 12)
						, updated_by = @USERNAME
						, updated_at = getdate()
				from temp_file_parses temp
				join tr_kuota_cuti_tahunans ct on ct.nik_simkar = temp. nik
					and ct.tgl_mulai  = temp.tgl_mulai_tahunan
					and ct.tgl_berakhir = temp.tgl_berakhir_tahunan
						--ct.id = temp.th_row
-- 				where th_row is not null;

				update cp 
					set terpakai = abs(45 - temp.kuota_cuti_panjang) --ABS(kuota_cuti_panjang - 45) 
					, updated_by = @USERNAME
					, updated_at = getdate()
				from temp_file_parses temp
				join tr_kuota_cuti_panjangs cp on cp.nik_simkar = temp.nik
				and cp.tgl_mulai  = temp.tgl_mulai_panjang
				and cp.tgl_berakhir = temp.tgl_berakhir_panjang
 --cp.id = temp.pj_row
	--			where pj_row is not null;
				-- kosongkan table temporary
				truncate table temp_file_parses;
			end
		END
;");
    }
}
