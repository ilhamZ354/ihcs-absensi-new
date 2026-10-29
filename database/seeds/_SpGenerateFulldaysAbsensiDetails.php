<?php

use Illuminate\Database\Seeder;

class _SpGenerateFulldaysAbsensiDetails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_fulldays_absensi_details') IS NOT NULL) DROP PROCEDURE sp_generate_fulldays_absensi_details;");
        DB::statement("CREATE PROCEDURE sp_generate_fulldays_absensi_details
				@tahun smallint, @bulan smallint
			AS
			BEGIN

			declare @maxDay smallint
				, @curDay smallint
				,	@id int
				,	@pin int
				,	@tgl date
				,	@checkin_time time
				,	@checkout_time time
				,	@telat_datang time
				,	@cepat_pulang time
				, @nik_simkar varchar(20)

			declare @tblNik table (
				nik varchar(20)
			)

			create table #tblAbsensi (
				id int,
				pin int,
				tgl date,
				checkin_time time,
				checkout_time time,
				telat_datang time,
				cepat_pulang time,
				nik_simkar varchar(20)
			)

			insert into @tblNik
			select nik_simkar from (
			select distinct nik_simkar from tr_absensi_details ad 
			where year(tgl) = @tahun and month(tgl) = @bulan
			) a 
			where nik_simkar not in (
				select nik_simkar from tr_absensi_details 
				where year(tgl) = @tahun and month(tgl) = @bulan
				group by nik_simkar, tgl
				having count(tgl) > 1
			)

			while exists(select * from @tblNik)
			begin
				declare @nik varchar(20)
				select top 1 @nik = nik from @tblNik order by nik asc
				select @maxDay = DATEDIFF(day, 
					cast((cast(@tahun as varchar) + '-'+ cast(@bulan as varchar)+'-01') as date)
					, dateadd(month, 1, cast((cast(@tahun as varchar) + '-'+ cast(@bulan as varchar)+'-01') as date)))
				select @id = id, @pin = pin, @tgl = tgl, @checkin_time = checkin_time, @checkout_time = checkout_time, @telat_datang = telat_datang, @cepat_pulang = cepat_pulang, @nik_simkar = nik_simkar from tr_absensi_details ad 
				where ad.nik_simkar = @nik
				and year(ad.tgl) = @tahun 
				and month(ad.tgl) = @bulan
				
			set @curDay = 1

			while @curDay <= @maxDay
			begin	
			insert into #tblAbsensi (id, pin, nik_simkar, tgl, checkin_time, checkout_time, telat_datang, cepat_pulang)
					select @id [id]
					, @pin [pin]
					, @nik_simkar [nik_simkar]
					, cast((cast(@tahun as varchar) + '-'+ cast(@bulan as varchar)+'-'+cast(@curDay as varchar)) as date)
					,(select checkin_time from tr_absensi_details where nik_simkar = @nik and year(tgl) = @tahun and month(tgl) = @bulan and day(tgl) = @curDay) [checkin_time]
					,(select checkout_time from tr_absensi_details where nik_simkar = @nik and year(tgl) = @tahun and month(tgl) = @bulan and day(tgl) = @curDay) [checkout_time]
					,(select telat_datang from tr_absensi_details where nik_simkar = @nik and year(tgl) = @tahun and month(tgl) = @bulan and day(tgl) = @curDay) [telat_datang]
					,(select cepat_pulang from tr_absensi_details where nik_simkar = @nik and year(tgl) = @tahun and month(tgl) = @bulan and day(tgl) = @curDay) [cepat_pulang]

					set @curDay = @curDay + 1
				end
				
				delete @tblNik where nik = @nik
			end

			IF (OBJECT_ID('tmp_absensi_details') IS NOT NULL) drop table tmp_absensi_details;

			select * into tmp_absensi_details from #tblAbsensi order by nik_simkar, tgl 

			drop table #tblAbsensi

			END;");
    }
}
