<?php

use Illuminate\Database\Seeder;

class FnGetStatusAbsensi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_status_absensi') IS NOT NULL) DROP FUNCTION fn_get_status_absensi;");
        DB::statement("CREATE FUNCTION [dbo].[fn_get_status_absensi] (
			@nik_simkar varchar(12), @tahun smallint, @bulan smallint, @tgl smallint
		)
		RETURNS char(5)
		AS 
		BEGIN

			/*
			THIS FUNCTION CREATED BY HARAPAN JAYA HAREFA @ 2018-10-25
			*/

			declare @result as char(5)

			-- make sure the periods needed is available in tp_formulir_absensi
			if (	
						(select count(id) from tp_formulir_headers 
							where (year(tgl_mulai) = @tahun or year(tgl_selesai) = @tahun) 
							and (month(tgl_mulai) = @bulan or month(tgl_selesai) = @bulan)
							and (day(tgl_mulai) = @tgl or day(tgl_selesai) = @tgl)
						) = 0
					) 
			BEGIN 
				--  if not return null
				select @result = NULL 
			END 

			ELSE 

			BEGIN

				-- in a function, we cant use temporary table, so create a table variable
				declare @tempFormulir table (
					id int, id_peserta int, status char(5), tgl_mulai date, tgl_selesai date
				)

				-- if rows available, save all rows to temporary table
				insert into @tempFormulir 
				select fa.id, fa.nik_simkar, fa.kode_jenis_absen as status, fa.tgl_mulai, fa.tgl_selesai 
				from tp_formulir_headers fa
				where fa.nik_simkar = @nik_simkar
				
				-- prepare some variables to storing data
				declare @idFormulir int
								, @id_peserta int
								, @status char(5)
								, @jmlHari int
								, @tgl_mulai as date
								, @tgl_selesai as date

				-- crate temp table to store list of result
					-- If(OBJECT_ID('#temp_formulir_absensi') Is Not Null) drop table #temp_formulir_absensi
				
				-- create temp formulir absensi table 
					declare @temp_formulir_absensi table (
						[id_formulir]  int,
						[id_peserta]  int,
						[status]  char(5),
						[hari]  varchar(15),
						[tgl]  date
					)
				
				-- do basic looping 
				while exists( select * from @tempFormulir) 
				begin
					select top 1 @idFormulir = id, @tgl_mulai = tgl_mulai, @tgl_selesai = tgl_selesai, @id_peserta = id_peserta, @status = status
					from @tempFormulir
					order by id asc
					
					-- init all variables
					declare @curDate as smallint = 1
					set @jmlHari = datediff(day, dateadd(day, -1, @tgl_mulai), @tgl_selesai)

					-- do looping
					while @curDate <= @jmlHari 
					begin
							insert into @temp_formulir_absensi (id_formulir, id_peserta, hari, tgl, status)
							select @idFormulir, @id_peserta, datename(dw, dateadd(day, @curDate -1, @tgl_mulai)), dateadd(day, @curDate -1, @tgl_mulai), @status
							set @curDate = @curDate + 1
					end

					delete @tempFormulir where id = @idFormulir
				end

				-- show result without saturday and sunday 
				select @result = tfa.status from @temp_formulir_absensi tfa
				where tfa.hari not in('Sunday', 'Saturday') 
				and year(tfa.tgl) = @tahun
				and month(tfa.tgl) = @bulan
				and day(tfa.tgl) = @tgl
				
				-- drop all temporary tables
				-- drop table @temp_formulir_absensi
				-- drop table @tempFormulir 
			END
			return (select @result)
		END;");
    }
}
