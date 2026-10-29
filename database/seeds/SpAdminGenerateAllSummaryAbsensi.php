<?php

use Illuminate\Database\Seeder;

class SpAdminGenerateAllSummaryAbsensi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_admin_generate_all_summary_absensi') IS NOT NULL) DROP PROCEDURE sp_admin_generate_all_summary_absensi");
        DB::statement("CREATE PROCEDURE sp_admin_generate_all_summary_absensi
		@tahun smallint, @bulan smallint
	AS
	BEGIN
		declare @temp as table (
			gol varchar (10)
		)

		declare @tempU as table (
			id int IDENTITY,
			kodeunitbagian varchar(5),
			namaunitbagian varchar(150),
			gol varchar (10)
		)

		-- truncate table tr_summary_absensi_bulanans

		INSERT INTO @temp 
		select '0' [gol] 
		union select '1' [gol] 
		union select 'HONOR' [gol] 

		insert into @tempU (kodeunitbagian, namaunitbagian, gol)
		select u.kodeunitbagian, u.namaunitbagian, t.*
		from unitbagianmaster u 
		join @temp t on 1=1
		where u.status = 1
		order by u.kodeunitbagian, gol
		-- select * from @tempU

		while exists (select * from @tempU)
		begin
			declare @tempID int
			, @tempKode varchar(5)
			, @tempNama varchar(100)
			, @tempGol varchar(10)
			select top 1 @tempID = id, @tempKode = kodeunitbagian, @tempNama=namaunitbagian, @tempGol = gol from @tempU	
			
			-- code here
			exec sp_admin_save_summary_absensi_bulanan_final @tahun, @bulan, @tempKode, @tempNama, '', @tempGol, ''
			
			delete from @tempU where id=@tempID
		end 
	END;");
    }
}
