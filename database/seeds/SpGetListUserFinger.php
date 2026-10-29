<?php

use Illuminate\Database\Seeder;

class SpGetListUserFinger extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_get_list_user_finger') IS NOT NULL) DROP PROCEDURE sp_get_list_user_finger");
        DB::statement("CREATE PROCEDURE [dbo].[sp_get_list_user_finger]
			@tahun smallint
			, @bulan smallint
			, @kodeunitbagian varchar(2)
			, @namaunitbagian varchar(100)
			, @namabagian varchar(100)
			, @golongan varchar(15)
			, @nama_karyawan varchar(100)
		AS
		BEGIN
			DECLARE @query nvarchar(max)
			, @query1 nvarchar(max)
			, @query2 nvarchar(max)

			IF(@golongan in ('0', '1'))
			BEGIN
				-- GOLONGAN 1
				set @query = 'select * from v_history_karyawan h '
				+ ' where h.tahun = ' + cast(@tahun as varchar(4))
				+ ' and h.bulan = ' + cast(@bulan as varchar(2))
				+ ' and exists (select * from tr_absensi_details a'
				+ ' where a.nik_simkar = h.nik'
				+ ' and year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and month(a.tgl) = ' + cast(@bulan as varchar(2))+')'
				
				
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query = @query + ' and namaunitbagian = ''' + @namaunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query = @query + ' and namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
					set @query = @query + ' and h.nama like ''%' + @nama_karyawan +'%'''

				IF(@golongan = '0')
					set @query = @query + ' and h.karpim = 0'
				ELSE 
					set @query = @query + ' and h.karpim = 1'
			END

			ELSE IF(@golongan in ('HONOR', 'MUSIMAN', 'OUTSOURCING', 'PIHAK3'))
			BEGIN
				-- GOLONGAN NONPTPN4
				SET @query = 'select '+ cast(@tahun as varchar(4)) +' tahun, '+cast(@bulan as varchar(2)) +' bulan, nik, nik_sap, nama_karyawan as nama, u.namaunitbagian, j.jabatan as namajabatan, b.namabagian, h.karpim, null [mkg], null [gol], null [sgol] '
				+ ' from tm_karyawan_nonptpn4 h '
				+ ' join tm_bagian b on b.kodebagian = h.kodebagian '
				+ ' join unitbagianmaster u on u.kodeunitbagian = h.unit '
				+ ' join tm_jabatan j on j.kodejabatan = h.kodejabatan '
				+ ' where 1 = 1'
				+ ' and exists (select * from tr_absensi_details a'
				+ ' where a.nik_simkar = h.nik'
				+ ' and year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and month(a.tgl) = ' + cast(@bulan as varchar(2))+')'
				-- + 'where h.status = ''01'''
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query = @query + ' and h.unit = ''' + @kodeunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query = @query + ' and b.namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
						set @query = @query + ' and h.nama_karyawan like ''%' + @nama_karyawan +'%'''
			END

			ELSE IF(@golongan = ('ALL'))
			BEGIN
				-- SEMUA GOLONGAN
				set @query1 = 'select * from v_history_karyawan h '
				+ ' where h.tahun = ' + cast(@tahun as varchar(4))
				+ ' and h.bulan = ' + cast(@bulan as varchar(2))
				+ ' and exists (select * from tr_absensi_details a'
				+ ' where a.nik_simkar = h.nik'
				+ ' and year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and month(a.tgl) = ' + cast(@bulan as varchar(2))+')'
				
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query1 = @query1 + ' and namaunitbagian = ''' + @namaunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query1 = @query1 + ' and namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
					set @query1 = @query1 + ' and h.nama like ''%' + @nama_karyawan +'%'''

				IF(@golongan = '0')
					set @query1 = @query1 + ' and h.karpim = 0'
				ELSE 
					set @query1 = @query1 + ' and h.karpim = 1'
				
				-- GOLONGAN NONPTPN4
				SET @query2 = 'select '+ cast(@tahun as varchar(4)) +' tahun, '+cast(@bulan as varchar(2)) +' bulan, nik, nik_sap, nama_karyawan as nama, u.namaunitbagian, j.jabatan as namajabatan, b.namabagian, h.karpim, null [mkg], null [gol], null [sgol] '
				+ 'from tm_karyawan_nonptpn4 h '
				+ 'join tm_bagian b on b.kodebagian = h.kodebagian '
				+ 'join unitbagianmaster u on u.kodeunitbagian = h.unit '
				+ 'join tm_jabatan j on j.kodejabatan = h.kodejabatan '
				+ 'where 1 = 1'
				+ ' and exists (select * from tr_absensi_details a'
				+ ' where a.nik_simkar = h.nik'
				+ ' and year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and month(a.tgl) = ' + cast(@bulan as varchar(2))+')'

				-- + 'where h.status = ''01'''
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query2 = @query2 + ' and h.unit = ''' + @kodeunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query2 = @query2 + ' and b.namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
						set @query2 = @query2 + ' and h.nama_karyawan like ''%' + @nama_karyawan +'%'''
				
				set @query = @query1 + ' union all ' + @query2
				
			END
			execute (@query)
		END;");
    }
}
