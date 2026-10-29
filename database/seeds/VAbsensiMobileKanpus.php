<?php

use Illuminate\Database\Seeder;

class VAbsensiMobileKanpus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_absensi_mobile_kanpus') IS NOT NULL) DROP VIEW v_absensi_mobile_kanpus");
    	DB::statement("CREATE VIEW v_absensi_mobile_kanpus 
    		AS
    		select 
            k.nik_sap
            , p.pin
            , a.nik_simkar
            , k.nama
            , k.unit
            , k.kodebagian
            , k.kodejabatan
            , a.tgl
            , a.check_in
            , a.check_out from (
                select pin, nik_simkar, tgl, min(ci) check_in , max(co) check_out
                from (
                        select null pin, nik nik_simkar, tanggal tgl, min(convert(varchar, convert(date, tanggal)) + ' ' + convert(varchar(8), jam_clockin )) ci, max(convert(varchar, convert(date, tanggal)) + ' ' + convert(varchar(8), jam_clockout )) co 
                        from tmp_mobile_absensis
                        group by nik, tanggal
                )a group by a.pin, a.nik_simkar, a.tgl
            ) a
            join v_history_karyawan k on k.nik = a.nik_simkar and k.tahun = year(a.tgl) and k.bulan = month(a.tgl) and k.unit = '00'
            join tp_pesertas p on p.nik_simkar = a.nik_simkar;");
    }
}
