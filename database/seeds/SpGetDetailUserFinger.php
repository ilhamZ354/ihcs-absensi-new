<?php

use Illuminate\Database\Seeder;

class SpGetDetailUserFinger extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_get_detail_user_finger') IS NOT NULL) DROP PROCEDURE sp_get_detail_user_finger");
        DB::statement("CREATE PROCEDURE [dbo].[sp_get_detail_user_finger]
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
				SET @query = 'select k.nik, k.nik_sap, k.nama, k.namabagian, k.namaunitbagian, a.tgl, a.jam_masuk, a.jam_pulang, a.checkin_time, a.checkout_time, a.telat_datang, a.cepat_pulang '
				+ ' from tp_pesertas as p '
				+ ' join tr_absensi_details as a on a.pin = p.pin '
				+ ' join v_history_karyawan as k on k.nik = p.nik_simkar and k.tahun = year(a.tgl) and k.bulan = month(a.tgl) '
				+ ' and year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and month(a.tgl) = ' + cast(@bulan as varchar(2))
				
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query = @query + ' and k.namaunitbagian = ''' + @namaunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query = @query + ' and k.namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
					set @query = @query + ' and k.nama like ''%' + @nama_karyawan +'%'''

				IF(@golongan = '0')
					set @query = @query + ' and k.karpim = 0'
				ELSE 
					set @query = @query + ' and k.karpim = 1'		
				-- set @query = @query + ' order by k.namaunitbagian, k.namabagian, k.nama, a.tgl '
				
			END

			ELSE IF(@golongan in ('HONOR', 'MUSIMAN', 'OUTSOURCING', 'PIHAK3'))
			BEGIN
				-- GOLONGAN NONPTPN4
				set @query = 'select k.nik, k.nik_sap, k.nama_karyawan as nama, b.namabagian, u.namaunitbagian, a.tgl, a.jam_masuk, a.jam_pulang, a.checkin_time, a.checkout_time, a.telat_datang, a.cepat_pulang '
				+ ' from tp_pesertas as p '
				+ ' join tr_absensi_details as a on a.pin = p.pin '
				+ ' join tm_karyawan_nonptpn4 as k on k.nik = p.nik_simkar '
				+ ' left join tm_bagian as b on b.kodebagian = k.kodebagian '
				+ ' left join unitbagianmaster as u on u.kodeunitbagian = k.unit '
				+ ' where year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and  month(a.tgl) = ' + cast(@bulan as varchar(2))				
				
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query = @query + ' and u.namaunitbagian = ''' + @namaunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query = @query + ' and b.namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
					set @query = @query + ' and k.nama_karyawan like ''%' + @nama_karyawan +'%'''
					
				--set @query = @query + ' order by u.namaunitbagian, b.namabagian, k.nama_karyawan, a.tgl '
			END

			ELSE IF(@golongan = ('ALL'))
			BEGIN
				-- SEMUA GOLONGAN
				SET @query1 = 'select k.nik, k.nik_sap, k.nama, k.namabagian, k.namaunitbagian, a.tgl, a.jam_masuk, a.jam_pulang, a.checkin_time, a.checkout_time, a.telat_datang, a.cepat_pulang '
				+ ' from tp_pesertas as p '
				+ ' join tr_absensi_details as a on a.pin = p.pin '
				+ ' join v_history_karyawan as k on k.nik = p.nik_simkar and k.tahun = year(a.tgl) and k.bulan = month(a.tgl) '
				+ ' and year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and month(a.tgl) = ' + cast(@bulan as varchar(2))
				
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query1 = @query1 + ' and k.namaunitbagian = ''' + @namaunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query1 = @query1 + ' and k.namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
					set @query1 = @query1 + ' and k.nama like ''%' + @nama_karyawan +'%'''

				IF(@golongan = '0')
					set @query1 = @query1 + ' and k.karpim = 0'
				ELSE 
					set @query1 = @query1 + ' and k.karpim = 1'
				-- --------------------------------------------------------------------------

				set @query2 = 'select k.nik, k.nik_sap, k.nama_karyawan as nama, b.namabagian, u.namaunitbagian, a.tgl, a.jam_masuk, a.jam_pulang, a.checkin_time, a.checkout_time, a.telat_datang, a.cepat_pulang '
				+ ' from tp_pesertas as p '
				+ ' join tr_absensi_details as a on a.pin = p.pin '
				+ ' join tm_karyawan_nonptpn4 as k on k.nik = p.nik_simkar '
				+ ' left join tm_bagian as b on b.kodebagian = k.kodebagian '
				+ ' left join unitbagianmaster as u on u.kodeunitbagian = k.unit '
				+ ' where year(a.tgl) = ' + cast(@tahun as varchar(4))
				+ ' and  month(a.tgl) = ' + cast(@bulan as varchar(2))				
				
				IF(@namaunitbagian IS NOT NULL AND @namaunitbagian <> '')
					set @query2 = @query2 + ' and u.namaunitbagian = ''' + @namaunitbagian +''''
				
				IF(@namabagian IS NOT NULL AND @namabagian <> '')
					set @query2 = @query2 + ' and b.namabagian = ''' + @namabagian +''''

				IF(@nama_karyawan IS NOT NULL AND @nama_karyawan <> '')
					set @query2 = @query2 + ' and k.nama_karyawan like ''%' + @nama_karyawan +'%'''		
				
				set @query = 'select * from (' + @query1 + ' union all ' + @query2 +') a '					
				
			END
			set @query = 'SELECT * FROM (' + @query + ') A '
			+ ' group by nik, nik_sap, nama, namabagian, namaunitbagian, tgl, jam_masuk, jam_pulang, checkin_time, checkout_time, telat_datang, cepat_pulang'
			+ ' order by namaunitbagian, namabagian, nama, tgl '
			execute(@query)
		END;");
    }
}
