<?php

use Illuminate\Database\Seeder;

class SpGenerateSummaryAbsensiPerBulan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_summary_absensi_per_bulan') IS NOT NULL) DROP PROCEDURE sp_generate_summary_absensi_per_bulan");
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_summary_absensi_per_bulan]
			 @tahun as int, @bulan as int
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
							@query as nvarchar(max)

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


			set @query = 'select id_peserta, nik_simkar, nama_mesin, tahun, bulan
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
				, '+@jl_ct+' as jl_ct
				, '+@jl_cp+' as jl_cp
				, '+@jl_cl+' as jl_cl
				, '+@jl_h1+' as jl_h1
				, '+@jl_h2+' as jl_h2
				, '+@jl_h3+' as jl_h3
				, '+@jl_ch+' as jl_ch 
				, '+@jl_na+' as jl_na 
			from (
			select 
				p.id id_peserta, p.nik_simkar, p.nama_mesin, year(ad.tgl) tahun, month(ad.tgl) bulan, day(ad.tgl) as tgl, 
					case when (ad.checkin_time is not null or ad.checkout_time is not null) then ''K'' 
						-- when datename(dw, ad.tgl) in (''saturday'', ''sunday'') then ''L''
						when ((select count(english_name) from dbo.fn_get_list_hari_kerja_by_id_peserta(p.id)) > 0) and datename(dw, ad.tgl) not in (select english_name from dbo.fn_get_list_hari_kerja_by_id_peserta(p.id)) then ''L''
						else 
								case when dbo.fn_get_status_absensi(dbo.fn_get_id_peserta(ad.pin), year(ad.tgl), month(ad.tgl), day(ad.tgl)) is not null then 
												dbo.fn_get_status_absensi(dbo.fn_get_id_peserta(ad.pin), year(ad.tgl), month(ad.tgl), day(ad.tgl))
										 else 
												''N/A''
											end
					end status
			from tr_absensi_details ad
			join tp_pesertas p on p.pin = ad.pin
			where 
			-- p.pin = 559 and 
			year(ad.tgl) = ' + cast(@tahun as nvarchar(4)) + '
			and
			month(ad.tgl) = ' + cast(@bulan as nvarchar(2)) + '
			-- and datename(dw, ad.tgl) not in (''saturday'', ''sunday'')
			) A
			pivot 
			(
				max(status)	
				for tgl in (' + @cols + ')
			) B'

			execute( @query )

			END;");
    }
}
