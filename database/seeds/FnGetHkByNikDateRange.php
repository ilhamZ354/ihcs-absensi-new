<?php

use Illuminate\Database\Seeder;

class FnGetHkByNikDateRange extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_hk_by_nik_date_range') IS NOT NULL) DROP FUNCTION fn_get_hk_by_nik_date_range;");
        DB::statement("CREATE FUNCTION dbo.fn_get_hk_by_nik_date_range (
			@nik_simkar varchar(15)
			, @mulai as date
			, @selesai as date
		)
		RETURNS smallint
		AS
		BEGIN
			declare @daycount smallint
			, @curloop smallint
			, @result smallint
			, @kode_absen varchar(5)

			select @kode_absen = kode_jenis_absen from tp_formulir_headers fh where fh.nik_simkar = @nik_simkar and tgl_mulai = @mulai and tgl_selesai = @selesai
			select @daycount = datediff(day, @mulai, @selesai)

			IF(@kode_absen = 'K') BEGIN
				SELECT @result = @daycount+1
			END 
			
			ELSE BEGIN			
				declare @tmpTable table(tgl date)

				set @curloop = 0 
				select @daycount = datediff(day, @mulai, @selesai)

				while (@daycount >= @curloop)
				begin
					insert into @tmpTable (tgl)
					select DATEADD(day, @curloop, @mulai)
					set @curloop = @curloop + 1
				end

				select @result = count(*) from @tmpTable as tmp
				where exists (
					select t.english_name from v_timetable t
					join tm_unit_kelompok_kerjas uk on uk.id_kelompok_kerja = t.id
					join tp_pesertas p on p.kodeunitbagian = uk.kodeunitbagian
					where uk.satpam = p.satpam	
					and datename(dw, tmp.tgl) = t.english_name
					and p.nik_simkar = @nik_simkar
				)
				-- drop table #tmpTable
			END
			return @result

		END;");
    }
}
