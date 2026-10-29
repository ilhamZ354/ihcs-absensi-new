<?php

use Illuminate\Database\Seeder;

class SpUpdateSummaryBasedFormulirKehadiran extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_update_summary_based_formulir_kehadiran') IS NOT NULL) DROP PROCEDURE sp_update_summary_based_formulir_kehadiran");
        DB::statement("CREATE PROCEDURE [dbo].[sp_update_summary_based_formulir_kehadiran]
			@id_formulir_header int = 1,
			@createdBy varchar(50)
		AS
		BEGIN
			declare @tempTbl as table (
			id smallint identity,
			nik varchar(15),
			tgl date,
			kode varchar(5)
		)

		declare @hNik varchar(15)
		, @hKode varchar(5)
		, @hMulai date
		, @hSelesai date
		, @hJmlHK smallint
		, @id smallint
		, @nik varchar(15)
		, @tgl date
		, @kode varchar(5)
		, @query varchar(max)

		select @hNik = nik_simkar, @hKode = kode_jenis_absen, @hMulai = tgl_mulai, @hSelesai = tgl_selesai from tp_formulir_headers where id = @id_formulir_header
		
		if(@hKode = 'K')
			insert into @tempTbl (nik, tgl, kode)
			select @hNik nik, tgl, @hKode kode from dbo.fn_get_list_date_range(@hMulai, dateadd(day, 1, @hSelesai))
		else
			insert into @tempTbl (nik, tgl, kode)	
			select @hNik nik, tgl, @hKode kode from dbo.fn_get_list_date_of_hk_pengajuan_cuti(@hNik, @hMulai, @hSelesai)

			select @hJmlHK = count(*) from @tempTbl
		begin try
			begin transaction
				while exists(select * from @tempTbl)
				BEGIN
					select @id = id, @nik = nik, @tgl = tgl, @kode = kode from @tempTbl
					set @query = 'update tr_summary_absensi_bulanans set ['+convert(varchar(2), day(@tgl))+']= '''+@kode+''', updated_by ='''+@createdBy +''', updated_at = getdate() where nik_simkar = '''+@nik+''' and tahun = '''+cast(year(@tgl) as varchar(4))+''' and bulan = '''+cast(month(@tgl) as varchar(2))+''''
					execute(@query)
					delete from @tempTbl where id = @id
				END
				
				if(@kode = 'KK') begin
					declare @kode1 VARCHAR(10) = 'K' 
					set @query = 'update tr_summary_absensi_bulanans set [jl_'+lower(replace(@kode1, '/', ''))+']= (jl_'+lower(replace(@kode1, '/', '')) +' + '+ cast(@hJmlHK as varchar)+'), updated_by ='''+@createdBy +''', updated_at = getdate() where nik_simkar = '''+@nik+''' and tahun = '''+cast(year(@tgl) as varchar(4))+''' and bulan = '''+cast(month(@tgl) as varchar(2))+''''
				end else begin
					set @query = 'update tr_summary_absensi_bulanans set [jl_'+lower(replace(@kode, '/', ''))+']= (jl_'+lower(replace(@kode, '/', '')) +' + '+ cast(@hJmlHK as varchar)+'), updated_by ='''+@createdBy +''', updated_at = getdate() where nik_simkar = '''+@nik+''' and tahun = '''+cast(year(@tgl) as varchar(4))+''' and bulan = '''+cast(month(@tgl) as varchar(2))+''''
				end
				execute(@query)
				set @query = 'exec sp_update_jumlah_absensi_bulanan '''+@nik+''', '+cast(year(@tgl) as varchar(4))+','+cast(month(@tgl) as varchar(2))+''
				select(@query)
				execute(@query)
				commit transaction
		end try
			begin catch
				rollback transaction
				SELECT error_message()
			end catch
		END;");
    }
}
