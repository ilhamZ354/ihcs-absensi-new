<?php

use Illuminate\Database\Seeder;

class VHistoryKaryawan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_history_karyawan') IS NOT NULL) DROP VIEW v_history_karyawan");
        DB::statement("CREATE VIEW v_history_karyawan
			AS
			-- select h.tahun, h.bulan, h.nik, h.nik_sap, k.nama, namaunitbagian, namajabatan, namabagian, h.karpim, h.mkg, LEFT(h.mkg, CHARINDEX('/',h.mkg) - 1) [gol], LEFT(h.mkg, CHARINDEX('/',h.mkg) - 2) [sgol]
			-- from T_HISTORY_KARYAWAN_BULANAN h
			-- join karyawan_ptpn4 k on k.nik = h.nik;

            SELECT
            h.tahun,
            h.bulan,
            h.nik,
            h.nik_sap,
            k.nama,
            h.unit,
            h.kodebagjab,
            bj.kodebagian,
            bj.kodejabatan,
            namaunitbagian,
            namajabatan,
            namabagian,
            h.karpim,
            h.mkg,
            LEFT (
                h.mkg,
                CHARINDEX('/', h.mkg) - 1
            ) [gol],
            LEFT (
                h.mkg,
                CHARINDEX('/', h.mkg) - 2
            ) [sgol]
        FROM
            T_HISTORY_KARYAWAN_BULANAN h
        left join [10.10.5.187].[SIMKAR].[dbo].TM_BAGIAN_JABATAN bj on bj.kodebagjab = h.kodebagjab
        JOIN karyawan_ptpn4 k ON k.nik = h.nik

        union all

        SELECT
            year(getdate()) tahun,
            month(getdate()) bulan,
            h.nik,
            h.nik_sap,
            h.nama,
            h.unit,
            h.kodebagjab,
            h.kodebagian,
            h.kodejabatan,
            u.namaunitbagian,
            j.jabatan namajabatan,
            b.namabagian,
            h.karpim,
            h.mkg, 
            case when CHARINDEX('/', h.mkg) > 0 then LEFT (h.mkg, CHARINDEX('/', h.mkg) - 1) else null end [gol],
            case when CHARINDEX('/', h.mkg) > 0 then LEFT (h.mkg, CHARINDEX('/', h.mkg) - 2) else null end [sgol]
        FROM
            karyawan_ptpn4 h
        inner join unitbagianmaster u on u.kodeunitbagian = h.unit
        inner join tm_bagian b on b.kodebagian = h.kodebagian
        inner join tm_jabatan j on j.kodejabatan = h.kodejabatan
        where h.status in ('01','38')
        and not exists(select * from T_HISTORY_KARYAWAN_BULANAN hh where hh.tahun = year(getdate()) and hh.bulan = month(getdate()))

        union all

        SELECT
            year(getdate()) tahun,
            month(getdate()) bulan,
            h.nik,
            h.nik_sap,
            h.nama_karyawan nama,
            h.unit,
            h.kodebagjab,
            h.kodebagian,
            h.kodejabatan,
            u.namaunitbagian,
            j.jabatan namajabatan,
            b.namabagian,
            h.karpim,
            null mkg,
            null gol,
            null sgol
        FROM
            tm_karyawan_nonptpn4 h
        inner join unitbagianmaster u on u.kodeunitbagian = h.unit
        inner join tm_jabatan j on j.kodejabatan = h.kodejabatan
        inner join tm_bagian b on b.kodebagian = h.kodebagian
        where h.status in ('01','38')
        and not exists(select * from T_HISTORY_KARYAWAN_BULANAN hh where hh.tahun = year(getdate()) and hh.bulan = month(getdate()));");
    }
}
