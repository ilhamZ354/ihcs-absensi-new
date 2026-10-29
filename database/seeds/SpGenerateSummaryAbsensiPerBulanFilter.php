<?php

use Illuminate\Database\Seeder;

class SpGenerateSummaryAbsensiPerBulanFilter extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_summary_absensi_per_bulan_filter') IS NOT NULL) DROP PROCEDURE sp_generate_summary_absensi_per_bulan_filter");    	
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_summary_absensi_per_bulan_filter]
			@tahun as int
			, @bulan as int
			, @unit varchar(5)
			, @nama_unit varchar(50)
			, @bagian varchar(50)
			, @sgol varchar(15)
			, @nama_karyawan varchar(50)
		AS
		BEGIN
			SET NOCOUNT ON

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
							@jl_cb as nvarchar(max),
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
			select @jl_k = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''K'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_l = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''L'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_s = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''S'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_s1 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''S-1'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_s2 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''S-2'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_m = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''M'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_m1 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''M-1'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_p1 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''P-1'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_p2 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''P-2'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_p3 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''P-3'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_p4 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''P-4'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_cb = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''CB'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_ct = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''CT'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_cp = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''CP'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_cl = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''CL'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_h1 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''H-1'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_h2 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''H-2'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_h3 = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''H-3'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_ch = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''CH'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_dl = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''DL'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)

			select @jl_na = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' = ''N/A'' then 1 else 0 end '
					from (
						select day(tgl) as tgl from tr_absensi_details where year(tgl) = @tahun and month(tgl) = @bulan
					) A group by tgl order by tgl
					for xml path(''), type
				).value('.', 'nvarchar(max)')
				,1,1,''
			)
			
			select @jl_abs = stuff (
				(
					select '+ case when ' + quotename(tgl) + ' in (''L'',''S'',''S-1'',''S-2'',''M'',''M-1'',''P-1'',''P-2'',''P-3'',''P-4'',''CB'',''CT'',''CP'',''CL'',''H-1'',''H-2'',''H-3'',''CH'',''DL'',''N/A'') then 1 else 0 end '
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
				, '+@jl_k+' as jl_k
				, '+@jl_l+' as jl_l
				, '+@jl_s+' as jl_s
				, '+@jl_dl+' as jl_dl
				, '+@jl_s1+' as jl_s1
				, '+@jl_s2+' as jl_s2
				, '+@jl_m+' as jl_m
				, '+@jl_m1+' as jl_m1
				, '+@jl_p1+' as jl_p1
				, '+@jl_p2+' as jl_p2
				, '+@jl_p3+' as jl_p3
				, '+@jl_p4+' as jl_p4
				, '+@jl_cb+' as jl_cb
				, '+@jl_ct+' as jl_ct
				, '+@jl_cp+' as jl_cp
				, '+@jl_cl+' as jl_cl
				, '+@jl_h1+' as jl_h1
				, '+@jl_h2+' as jl_h2
				, '+@jl_h3+' as jl_h3
				, '+@jl_ch+' as jl_ch 
				, '+@jl_na+' as jl_na 
				, '+@jl_abs+' as jl_abs
			from (
			select 
				p.pin id_peserta, p.nik_simkar, h.nik_sap, p.nama_mesin, year(ad.tgl) tahun, month(ad.tgl) bulan, day(ad.tgl) as tgl, 
					case
						when dbo.fn_get_formulir_absensi_by_nik_tgl(p.nik_simkar, ad.tgl) = ''K'' then dbo.fn_get_formulir_absensi_by_nik_tgl(p.nik_simkar, ad.tgl)
						when l.tgl is not null then ''CB''
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
			left join tr_libur_cuti_bersamas l on l.tgl = ad.tgl and l.is_cb = 1
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
			execute ( @query )
		END;");
    }
}
