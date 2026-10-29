<?php

use Illuminate\Database\Seeder;

class SpSaveSummaryAbsensi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('SP_SAVE_SUMMARY_ABSENSI') IS NOT NULL) DROP PROCEDURE SP_SAVE_SUMMARY_ABSENSI");
        DB::statement("CREATE PROCEDURE [dbo].[SP_SAVE_SUMMARY_ABSENSI]
					@tahun as int
					, @bulan as int
					, @unit varchar(5)
					, @nama_unit varchar(50)
					, @bagian varchar(50)
					, @sgol varchar(15)
					, @nama_karyawan varchar(50)
		AS
		BEGIN
					declare @cols as nvarchar(max),
									@jl_k as nvarchar(max),
									@jl_s as nvarchar(max),
									@jl_dl as nvarchar(max),
									@jl_s1 as nvarchar(max),
									@jl_s2 as nvarchar(max),
									@jl_m as nvarchar(max),
									@jl_m1 as nvarchar(max),
									@jl_p1 as nvarchar(max),
									@jl_p2 as nvarchar(max),
									@jl_p3 as nvarchar(max),
									@jl_p4 as nvarchar(max),
									@jl_ct as nvarchar(max),
									@jl_cp as nvarchar(max),
									@jl_cl as nvarchar(max),
									@jl_h1 as nvarchar(max),
									@jl_h2 as nvarchar(max),
									@jl_h3 as nvarchar(max),
									@jl_ch as nvarchar(max),
									@jl_l as nvarchar(max),
									@jl_na as nvarchar(max),
									@jl_abs as nvarchar(max),
									@query as nvarchar(max), 
									@_join varchar(max), 
									@_where varchar(max)

					select @cols = stuff (
						(
							select ',' + quotename(tgl)  
							from (
									select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
							) A group by tgl order by tgl
						for xml path(''), type
						).value('.', 'nvarchar(max)')
						,1,1,''
					)

		if(@sgol in ('0', '1', '2', '3'))
			begin
				set @_join = 'join v_history_karyawan h on h.nik = p.nik_simkar and h.tahun = year(ad.tgl) and h.bulan = month(ad.tgl)'
				set @_where = ''
				if(@nama_karyawan is not null and @nama_karyawan <> '')
				begin 
					set @_where = @_where + ' and h.nama like ''%'+@nama_karyawan+'%'''
				end
			
				if(@nama_unit is not null and @nama_unit <> '')
				begin 
					set @_where = @_where + ' and h.namaunitbagian = '''+@nama_unit+''''
				end
			
				if(@bagian is not null and @bagian <> '')
				begin 
					set @_where = @_where + ' and h.namabagian = '''+@bagian+''''
				end
			
				if(@sgol is not null and @sgol <> '')
				begin 
					if(@sgol = '0')
						set @_where = @_where + ' and h.sgol in (''I'', ''II'')'
					
					if(@sgol = '1')
						set @_where = @_where + ' and h.sgol in (''III'', ''IV'')'
						
				end
			end
			
			else if(@sgol in ('HONOR', 'MUSIMAN', 'OUTSOURCING', 'PIHAK3'))
			begin
				set @_join = 'join tm_karyawan_nonptpn4 h on h.nik = p.nik_simkar '
				set @_join = @_join + ' join (select kodeunitbagian, namaunitbagian from unitbagianmaster) hu on hu.kodeunitbagian = p.kodeunitbagian '
				set @_join = @_join + ' join (select kodebagian, namabagian from tm_bagian) hb on hb.kodebagian = h.kodebagian '
				
				set @_where = ''
				if(@nama_karyawan is not null and @nama_karyawan <> '')
				begin 
					set @_where = @_where + ' and h.nama_karyawan like ''%'+@nama_karyawan+'%'''
				end
				
				if(@nama_unit is not null and @nama_unit <> '')
				begin 
					set @_where = @_where + ' and hu.namaunitbagian = '''+@nama_unit+''''
				end
				
				if(@bagian is not null and @bagian <> '')
				begin 
					set @_where = @_where + ' and hb.namabagian = '''+@bagian+''''
				end
				
				if(@sgol is not null and @sgol <> '')
				begin
					set @_where = @_where + ' and h.tipe_karyawan = '''+@sgol+''''
				end
			end


					set @query = 'select id_peserta, nik_simkar, nik_sap, nama_mesin, tahun, bulan
						, ' + @cols + '
					from (
					select 
						p.pin id_peserta, p.nik_simkar, h.nik_sap, p.nama_mesin, year(ad.tgl) tahun, month(ad.tgl) bulan, day(ad.tgl) as tgl, 
							case
								when dbo.fn_get_formulir_absensi_by_nik_tgl(p.nik_simkar, ad.tgl) = ''K'' then dbo.fn_get_formulir_absensi_by_nik_tgl(p.nik_simkar, ad.tgl)
								when ((select count(english_name) from dbo.fn_get_list_hari_kerja_by_id_peserta(p.id)) > 0) and datename(dw, ad.tgl) not in (select english_name from dbo.fn_get_list_hari_kerja_by_id_peserta(p.id)) then ''L''
								when dbo.fn_is_libur_atau_cuti_bersama(ad.tgl) > 0 then ''L''
								when (dbo.fn_get_formulir_absensi_by_nik_tgl(p.nik_simkar, ad.tgl) is not null and dbo.fn_get_formulir_absensi_by_nik_tgl(p.nik_simkar, ad.tgl) <> ''K'') then dbo.fn_get_formulir_absensi_by_nik_tgl(p.nik_simkar, ad.tgl)
								when (ad.checkin_time is not null or ad.checkout_time is not null) then ''K''						
								else 
										case when dbo.fn_get_status_absensi(dbo.fn_get_id_peserta(ad.pin), year(ad.tgl), month(ad.tgl), day(ad.tgl)) is not null then 
														dbo.fn_get_status_absensi(dbo.fn_get_id_peserta(ad.pin), year(ad.tgl), month(ad.tgl), day(ad.tgl))
												 else 
														''N/A''
													end
							end status
					from tr_absensi_details ad
					join tp_pesertas p on p.pin = ad.pin
					'+@_join+'
					where 1=1
					and year(ad.tgl) = ' + cast(@tahun as nvarchar(4)) + '
					and month(ad.tgl) = ' + cast(@bulan as nvarchar(2)) + '
					'+@_where+'
					) A
					pivot 
					(
						max(status)	
						for tgl in (' + @cols + ')
					) B'
					
		declare @finalQuery nvarchar(max) = 'insert into tr_summary_absensi_bulanans (id_peserta, nik_simkar, nik_sap, nama, tahun, bulan, ' + @cols + ', created_by, created_at)'
					+ 'select *, ''SP_SAVE_SUMMARY_ABSENSI'', getdate() from (' + @query +') a where not exists (select * from tr_summary_absensi_bulanans b where b.nik_simkar = a.nik_simkar and a.tahun = b.tahun and a.bulan = b.bulan)'
					print( @finalQuery )
					execute( @finalQuery )
		END;");
    }
}
