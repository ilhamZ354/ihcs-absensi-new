<?php

use Illuminate\Database\Seeder;

class VKaryawanAll extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_karyawan_all') IS NOT NULL) DROP VIEW v_karyawan_all");    	
        DB::statement("CREATE VIEW v_karyawan_all 
        As
	    SELECT
			k.nik,
			k.nik_sap,
			k.status,
			k.nama_karyawan AS nama,
			k.tipe_karyawan,
			CONVERT ( VARCHAR ( 20 ), k.tgl_bekerja, 105 ) AS tanggal_bekerja,
			k.unit,
			u.namaunitbagian AS namaunit,
			k.kodebagian AS bagian,
			b.namabagian AS namabagian,
			k.kodejabatan AS jabatan,
			j.jabatan AS namajabatan,
			pj.tgl_mulai AS tgl_mulai_panjang,
			pj.tgl_berakhir AS tgl_berakhir_panjang,
			pj.kuota - pj.terpakai AS kuota_cuti_panjang,
			th.tgl_mulai AS tgl_mulai_tahunan,
			th.tgl_berakhir AS tgl_berakhir_tahunan,
			th.kuota - th.terpakai AS kuota_cuti_tahunan,
			th.terpakai as terpakai_tahunan
		FROM
			tm_karyawan_nonptpn4 AS k
			INNER JOIN unitbagianmaster AS u ON k.unit = u.kodeunitbagian
			INNER JOIN tm_bagian AS b ON k.kodebagian = b.kodebagian
			INNER JOIN tm_jabatan AS j ON k.kodejabatan = j.kodejabatan
			LEFT JOIN tp_pesertas AS p ON k.nik = p.nik_simkar
			LEFT JOIN (select * from v_cuti_tahunan_with_cb where getdate() between tgl_mulai and tgl_berakhir) AS th ON th.nik_simkar = k.nik
			LEFT JOIN (select * from tr_kuota_cuti_panjangs where getdate() between tgl_mulai and tgl_berakhir) AS pj ON pj.nik_simkar = k.nik
		WHERE
			k.status = '01' UNION ALL
		SELECT
			k.nik,
			k.nik_sap,
			k.status,
			k.nama,
			CAST ( k.karpim AS VARCHAR ) AS tipe_karyawan,
			CONVERT ( VARCHAR ( 20 ), k.tanggal_bekerja, 105 ) AS tanggal_bekerja,
			k.unit,
			u.namaunitbagian AS namaunit,
			k.kodebagian AS bagian,
			b.namabagian AS namabagian,
			k.kodejabatan AS jabatan,
			j.jabatan AS namajabatan,
			pj.tgl_mulai AS tgl_mulai_panjang,
			pj.tgl_berakhir AS tgl_berakhir_panjang,
			pj.kuota - pj.terpakai AS kuota_cuti_panjang,
			th.tgl_mulai AS tgl_mulai_tahunan,
			th.tgl_berakhir AS tgl_berakhir_tahunan,
			th.kuota - th.terpakai AS kuota_cuti_tahunan,
			th.terpakai as terpakai_tahunan
		FROM
			karyawan_ptpn4 AS k
			INNER JOIN unitbagianmaster AS u ON k.unit = u.kodeunitbagian
			INNER JOIN tm_bagian AS b ON k.kodebagian = b.kodebagian
			INNER JOIN tm_jabatan AS j ON k.kodejabatan = j.kodejabatan
			LEFT JOIN tp_pesertas AS p ON k.nik = p.nik_simkar
			LEFT JOIN (select * from v_cuti_tahunan_with_cb where getdate() between tgl_mulai and tgl_berakhir) AS th ON th.nik_simkar = k.nik
			LEFT JOIN (select * from tr_kuota_cuti_panjangs where getdate() between tgl_mulai and tgl_berakhir) AS pj ON pj.nik_simkar = k.nik
		WHERE
			k.status = '01';");
    }
}
