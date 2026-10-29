<?php

use Illuminate\Database\Seeder;

class SpGenerateFulldaysAbsensiDetails extends Seeder
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
			@tahun smallint
			, @bulan smallint
		AS
		BEGIN
			SET NOCOUNT ON;
			
			DECLARE @maxDay smallint
			, @s date
			, @e date
			, @pin smallint

			select @s = cast((cast(@tahun as varchar) + '-'+ cast(@bulan as varchar)+'-01') as date)
						, @e = dateadd(day, -1 ,dateadd(month, 1, cast((cast(@tahun as varchar) + '-'+ cast(@bulan as varchar)+'-01') as date)))

			create table #tmp_absensi (
			id int identity,
			pin smallint
			, tgl date
			, jam_masuk time
			, jam_pulang time
			, checkin_time time
			, checkout_time time
			, telat_datang time
			, cepat_pulang time
			)

			select pin into #tp_peserta from tp_pesertas p
			where exists (
			select pin from tr_absensi_details a where a.pin = p.pin
			)

			while exists(select * from #tp_peserta)
			BEGIN
					select top 1 @pin = pin from #tp_peserta order by pin asc

			insert into #tmp_absensi (pin, tgl, s.jam_masuk, s.jam_pulang, checkin_time, checkout_time)
			select @pin, c.tgl, s.jam_masuk, s.jam_pulang, checkin_time = coalesce(s.checkin_time, null), checkout_time = coalesce(s.checkout_time, null)
			from 
			(
				select top (datediff(day, @s, @e) + 1)
					dateadd(day, row_number() over (order by number)-1, @s)
				from [master].dbo.spt_values
				where [type] = N'P' order by number
			) as c(tgl)
			left outer join (select * from tr_absensi_details where pin = @pin) as s on c.tgl = s.tgl
			where c.tgl >= @s
			and c.tgl < dateadd(day, 1, @e)

			delete #tp_peserta where pin = @pin
			END
	

			insert into tr_absensi_details (nik_simkar, pin, tgl, jam_masuk, jam_pulang, checkin_time, checkout_time, telat_datang, cepat_pulang, created_by)
			select p.nik_simkar, a.pin, tgl, jam_masuk, jam_pulang, checkin_time, checkout_time, telat_datang, cepat_pulang, 'system' [created_by] 
			from #tmp_absensi a
			join tp_pesertas p on p.pin = a.pin
			where not exists( 
				select * from tr_absensi_details ad
				where ad.tgl = a.tgl
				and ad.pin = a.pin
			)

			drop table #tp_peserta
			drop table #tmp_absensi

		END;");
    }
}
