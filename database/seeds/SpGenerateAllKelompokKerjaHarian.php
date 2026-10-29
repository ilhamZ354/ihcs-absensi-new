<?php

use Illuminate\Database\Seeder;

class SpGenerateAllKelompokKerjaHarian extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_all_kelompok_kerja_harian') IS NOT NULL) DROP PROCEDURE sp_generate_all_kelompok_kerja_harian");
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_all_kelompok_kerja_harian]
			@created_by varchar(50) = 'System'
		AS 
		BEGIN
			select id 
			into #dev_kelompok_kerja_detail
			from tr_kelompok_kerja_details
			where charindex('-', rentang_hari) > 0

			declare @pId int

			while exists (select * from #dev_kelompok_kerja_detail)
			begin
				select top 1 @pId = id
				from #dev_kelompok_kerja_detail
				order by id asc

				-- men-generate kelompok kerja harian berdasarkan rentang hari
				exec sp_generate_kelompok_kerja_harian_by_id_detail @pId, @created_by
				delete #dev_kelompok_kerja_detail where id = @pId

			end

			drop table #dev_kelompok_kerja_detail

			-- men-generate kelompok kerja harian tanpa rentang hari
			insert into tr_kelompok_kerja_harians(id_kelompok_kerja_detail, nama_hari, created_by)
			select d.id, i.nama_hari, @created_by
			from tr_kelompok_kerja_details d 
			join tr_index_nama_haris i on i.nama_hari = d.rentang_hari
			where charindex('-', rentang_hari) = 0
			and not exists (
				select * from tr_kelompok_kerja_harians kkh where id_kelompok_kerja_detail = d.id and nama_hari = i.nama_hari
			)
		END;");
    }
}
