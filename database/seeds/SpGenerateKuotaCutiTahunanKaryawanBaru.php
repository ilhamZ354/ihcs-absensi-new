<?php

use Illuminate\Database\Seeder;

class SpGenerateKuotaCutiTahunanKaryawanBaru extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('sp_generate_kuota_cuti_tahunan_karyawan_baru') IS NOT NULL) DROP PROCEDURE sp_generate_kuota_cuti_tahunan_karyawan_baru");
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_kuota_cuti_tahunan_karyawan_baru]
        	AS 
        	declare @tahun int = year(getdate())
        	BEGIN
        	insert into tr_kuota_cuti_tahunans (nik_simkar, kuota, tgl_mulai, tgl_berakhir, created_by)
        		select *, 'System' from (
        		select 
        		nik, 0 [kuota], this_year [tgl_mulai], dateadd(day, -1 , dateadd(month, 12, this_year)) [tgl_berakhir]
        		from (
        		select 
        		nik
        		, nama
        		, tanggal_bekerja  
        		, dateadd(year, @tahun  - year(tanggal_bekerja), tanggal_bekerja) [this_year]  
        		from karyawan_ptpn4 
        		where status = '01'
        		) a
        		where year(this_year) = @tahun
        		and datediff(month, dateadd(day, -1, tanggal_bekerja), this_year) < 12
        		) k
        		where not exists(
        		select * from tr_kuota_cuti_tahunans ct
        		where ct.nik_simkar = k.nik
        		and ct.tgl_mulai = k.tgl_mulai
        		and ct.tgl_berakhir = k.tgl_berakhir
        		)
        	END;");
    }
}
