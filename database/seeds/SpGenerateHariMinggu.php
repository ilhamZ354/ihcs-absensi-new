<?php

use Illuminate\Database\Seeder;

class SpGenerateHariMinggu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_generate_hari_minggu') IS NOT NULL) DROP PROCEDURE sp_generate_hari_minggu");
        DB::statement("CREATE PROCEDURE [dbo].[sp_generate_hari_minggu]
			@tahun as int
		AS BEGIN
			insert into tr_libur_cuti_bersamas(tgl, uraian, keterangan, created_by, created_at)
			
			select tgl, 'Hari Minggu', 'Tidak ada jadwal kerja, tanggal dicetak merah', 'System', getdate() 
			from dbo.fn_get_list_date_range(convert(varchar(4), @tahun)+'0101', dateadd(year, 1, convert(varchar(4), @tahun)+'0101')) a
			where DATENAME(dw, tgl) = 'Sunday'
			and not exists(
				select tgl, uraian, keterangan, created_by, created_at from tr_libur_cuti_bersamas b
				where a.tgl = b.tgl
			);
		END;");
    }
}
