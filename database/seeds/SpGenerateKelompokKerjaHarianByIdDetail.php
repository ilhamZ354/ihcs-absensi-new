<?php

use Illuminate\Database\Seeder;

class SpGenerateKelompokKerjaHarianByIdDetail extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_kelompok_kerja_harian_by_id_detail') IS NOT NULL) DROP PROCEDURE sp_generate_kelompok_kerja_harian_by_id_detail");
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_kelompok_kerja_harian_by_id_detail]
			@pId as int, 
			@created_by varchar(15) = 'System'
		AS BEGIN

			-- buat temporary table untuk menampung data
			create table #temp_kelompok_kerja_harian
			(
				id_kelompok_kerja_detail int, 
				nama_hari varchar(15),
				created_by varchar(15)
			)

			-- Mendefinisikan variabel yang dibutuhkan
			DECLARE @id_kelompok_kerja_detail int

			-- Menyimpan data ke dalam variable-variable yang sudah tersedia
			SELECT top 1 @id_kelompok_kerja_detail=id FROM tr_kelompok_kerja_details where id = @pId

			-- Membuat variable yang dibutuhkan untuk proses looping
			DECLARE @mulai int
			DECLARE @akhir int

			-- memecah rentang hari ke dalam daftar hari
			select @mulai = min([nomor]) , @akhir = max([nomor]) from 
			(
				select days.id as id_kelompok_kerja_detail, days.rentang_hari as nama_hari, i.id [nomor] from 
				(
					SELECT A.id,  
						Split.a.value('.', 'VARCHAR(100)') AS rentang_hari  
						FROM  
						(
							SELECT id,  
							CAST ('<M>' + REPLACE(rentang_hari, '-', '</M><M>') + '</M>' AS XML) AS rentang_hari  
							FROM  tr_kelompok_kerja_details where (CHARINDEX('-', rentang_hari) > 0) and id = @pId
						) AS A CROSS APPLY rentang_hari.nodes ('/M') AS Split(a) 
				) as days
				join tr_index_nama_haris i on i.nama_hari = days.rentang_hari
			) A

			-- do loop and store result to temporary table
			WHILE @mulai <= @akhir
			BEGIN		 
					insert into tr_kelompok_kerja_harians (id_kelompok_kerja_detail, nama_hari, created_by)
					select id_kelompok_kerja_detail, nama_hari, created_by from (
						select @id_kelompok_kerja_detail [id_kelompok_kerja_detail], (select nama_hari from tr_index_nama_haris where [id] = @mulai) [nama_hari], @created_by [created_by]
					) A 
					where not exists (
						select * from tr_kelompok_kerja_harians 
							where id_kelompok_kerja_detail = A.id_kelompok_kerja_detail and nama_hari = A.nama_hari
					)
				SET @mulai = @mulai + 1
			END

			-- insert records to the real table divisi_detail_jadwal_harian
			if((select count(*) from tr_kelompok_kerja_harians where id_kelompok_kerja_detail in(select id_kelompok_kerja_detail from #temp_kelompok_kerja_harian)) > 0) begin select 0 end
			else begin
				insert into tr_kelompok_kerja_harians (id_kelompok_kerja_detail, nama_hari, created_by)
				select * from #temp_kelompok_kerja_harian tkh 
				where not exists (
					select * from tr_kelompok_kerja_harians 
						where id_kelompok_kerja_detail = tkh.id_kelompok_kerja_detail and nama_hari = tkh.nama_hari
				)
			end
			drop table #temp_kelompok_kerja_harian
			
		END;");
    }
}
