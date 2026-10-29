<?php

use Illuminate\Database\Seeder;

class SpGenerateKuotaCutiPanjang extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('sp_generate_kuota_cuti_panjang') IS NOT NULL) DROP PROCEDURE sp_generate_kuota_cuti_panjang");
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_kuota_cuti_panjang]
			@created_by varchar(50) = 'sp_generate_kuota_cuti_panjang'
		AS 
		BEGIN
			insert into tr_kuota_cuti_panjangs (nik_simkar, kuota, tgl_mulai, tgl_berakhir, created_by)
			select * from (
				select 
					nik_simkar
					, 45 kuota
					, CONVERT(DATE, DATEADD(month, (DATEDIFF(month, tanggal_bekerja_perbaikan, getdate()) / 72) * 72, tanggal_bekerja_perbaikan)) [tgl_mulai]
					, CONVERT(DATE, DATEADD(DAY, -1, DATEADD(month, ((DATEDIFF(month, tanggal_bekerja_perbaikan, getdate()) / 72) * 72) + 72, tanggal_bekerja_perbaikan))) [tgl_berakhir]
					, @created_by created_by
				from (
					select 
					nik [nik_simkar]
					, tanggal_bekerja [tgl_kerja_sebenarnya]
					, case when tanggal_bekerja < '20000101' then DATEADD(year, abs(year(tanggal_bekerja) - 2000), tanggal_bekerja) else tanggal_bekerja end [tanggal_bekerja_perbaikan]
					from karyawan_ptpn4
					where status = '01'			
					and DATEDIFF(year, tanggal_bekerja, getdate()) >= 6
				) a
			) a
			where not exists (
				select * from tr_kuota_cuti_panjangs b 
					where b.nik_simkar = a.nik_simkar
					and b.tgl_mulai = a.tgl_mulai
					and b.tgl_berakhir = a.tgl_berakhir
			)
			order by tgl_mulai asc

		END;");
    }
}
