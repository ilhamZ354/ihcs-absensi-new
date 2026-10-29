<?php

use Illuminate\Database\Seeder;

class SpInsertOrRewriteAbsensiDetail extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_insert_or_overwrite_absensi_detail') IS NOT NULL) DROP PROCEDURE sp_insert_or_overwrite_absensi_detail");
    	DB::statement("CREATE PROCEDURE [dbo].[sp_insert_or_overwrite_absensi_detail]
                            @pIdLogUpload int,
                            @pCreatedBy varchar(50)
                        AS
                        BEGIN

                            DECLARE @id int,
                                @tahun smallint,
                                @bulan smallint,
                                @jenis_data varchar(20),
                                @kode_unit varchar(5),
                                @kode_bagian varchar(15),
                                @nik_peserta varchar(15),
                                @nama_peserta varchar(100),
                                @file_absensi varchar(250),
                                @affected_rows int

                            select top 1 @id = id, @tahun = tahun, @bulan = bulan, @jenis_data = jenis_data, @kode_unit = kode_unit, @kode_bagian = kode_bagian, @nik_peserta = nik_peserta, @nama_peserta = nama_peserta, @file_absensi = file_absensi
                            from tp_log_upload_absensis where id = @pIdLogUpload;

                            print(@jenis_data)

                            if(@jenis_data = 'semua') begin
                                print('menjalankan SEMUA')
                                delete ad from tr_absensi_details ad
                                where exists(
                                    select * from tr_absensi_details_raw adn 
                                    where year(adn.tgl) = year(ad.tgl)
                                    and month(adn.tgl) = month(ad.tgl)
                                    and year(adn.tgl) = @tahun 
                                    and month(adn.tgl)=@bulan
                                )
                                
                                insert into tr_absensi_details (pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, created_by)
                                select pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, @pCreatedBy from tr_absensi_details_raw adn 
                                where year(adn.tgl) = @tahun and month(adn.tgl)=@bulan;

                                delete adn from tr_absensi_details_raw adn 
                                where year(adn.tgl) = @tahun and month(adn.tgl)=@bulan
                            end 


                            if(@jenis_data = 'unit') begin
                                print('menjalankan UNIT')
                                delete ad from tr_absensi_details ad
                                where exists(
                                    select * from tr_absensi_details_raw adn 
                                    where year(adn.tgl) = year(ad.tgl)
                                    and month(adn.tgl) = month(ad.tgl)
                                    and (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                    and adn.nik_simkar in (
                                        select nik from karyawan_ptpn4 where status = '01'
                                        and unit = @kode_unit
                                        union
                                        select nik from tm_karyawan_nonptpn4 where status = '01'
                                        and unit = @kode_unit
                                    )
                                    and adn.pin = ad.pin
                                )

                                insert into tr_absensi_details (pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, created_by)
                                select pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, @pCreatedBy from tr_absensi_details_raw adn 
                                    where (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                    and adn.nik_simkar in (
                                        select nik from karyawan_ptpn4 where status = '01'
                                        and unit = @kode_unit
                                        union
                                        select nik from tm_karyawan_nonptpn4 where status = '01'
                                        and unit = @kode_unit
                                    );

                                    delete adn from tr_absensi_details_raw adn 
                                    where (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                    and adn.nik_simkar in (
                                        select nik from karyawan_ptpn4 where status = '01'
                                        and unit = @kode_unit
                                        union
                                        select nik from tm_karyawan_nonptpn4 where status = '01'
                                        and unit = @kode_unit
                                    )
                            end

                            
                            if(@jenis_data = 'bagian') begin
                                print('menjalankan BAGIAN')
                                delete ad from tr_absensi_details ad
                                where exists(
                                    select * from tr_absensi_details_raw adn 
                                    where year(adn.tgl) = year(ad.tgl)
                                    and month(adn.tgl) = month(ad.tgl)
                                    and (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                    and adn.nik_simkar in (
                                        select nik from karyawan_ptpn4 where status = '01'
                                        and unit = @kode_unit and kodebagian = @kode_bagian
                                        union
                                        select nik from tm_karyawan_nonptpn4 where status = '01'
                                        and unit = @kode_unit and kodebagian = @kode_bagian
                                    )
                                    and adn.pin = ad.pin
                                );
                                
                                insert into tr_absensi_details (pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, created_by)
                                select pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, @pCreatedBy from tr_absensi_details_raw adn 
                                    where (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                    and adn.nik_simkar in (
                                        select nik from karyawan_ptpn4 where status = '01'
                                        and unit = @kode_unit and kodebagian = @kode_bagian
                                        union
                                        select nik from tm_karyawan_nonptpn4 where status = '01'
                                        and unit = @kode_unit and kodebagian = @kode_bagian
                                    )

                                    delete adn from tr_absensi_details_raw adn 
                                    where (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                    and adn.nik_simkar in (
                                        select nik from karyawan_ptpn4 where status = '01'
                                        and unit = @kode_unit and kodebagian = @kode_bagian
                                        union
                                        select nik from tm_karyawan_nonptpn4 where status = '01'
                                        and unit = @kode_unit and kodebagian = @kode_bagian
                                    )
                            end

                            if(@jenis_data = 'personal') begin
                                print('menjalankan PERSONAL')
                                delete ad from tr_absensi_details ad
                                where exists(
                                    select * from tr_absensi_details_raw adn 
                                    where year(adn.tgl) = year(ad.tgl)
                                    and month(adn.tgl) = month(ad.tgl)
                                    and (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                    and adn.nik_simkar = @nik_peserta
                                    and adn.pin = ad.pin
                                )
                                
                                insert into tr_absensi_details (pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, created_by)
                                select pin,nik_simkar,tgl,jam_masuk, jam_pulang,checkin_time,checkout_time,telat_datang,cepat_pulang, @pCreatedBy from tr_absensi_details_raw adn 
                                where (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                and adn.nik_simkar = @nik_peserta;
                                
                                delete adn from tr_absensi_details_raw adn 
                                where (year(adn.tgl) = @tahun and month(adn.tgl)=@bulan)
                                and adn.nik_simkar = @nik_peserta
                            end

                            -- TRUNCATE TABLE tr_absensi_details_raw
                            IF (OBJECT_ID('tmp_absensi_details') IS NOT NULL) drop table tmp_absensi_details;

                        END");
    }
}
