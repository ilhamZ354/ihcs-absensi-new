<?php

use Illuminate\Database\Seeder;

class SpGetFormulirDetailById extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::statement("IF (OBJECT_ID('sp_get_formulir_detail_by_id') IS NOT NULL) DROP PROCEDURE sp_get_formulir_detail_by_id");
       DB::statement("CREATE PROCEDURE sp_get_formulir_detail_by_id
       	@id_formulir_header int
       	AS
       	BEGIN
	       	SELECT
	       	h.id,
	       	h.nik_simkar,
	       	isnull(
	       	k.nama,
	       	( SELECT nama FROM karyawan_ptpn4 as ka WHERE ka.nik = h.nik_simkar )) AS nama,
	       	isnull( k.namaunitbagian, know.namaunitbagian ) AS nama_unit,
	       	isnull( k.namabagian, know.namabagian ) AS nama_bagian,
	       	j.nama AS nama_jenis_absen,
	       	h.alasan,
	       	CASE WHEN h.kode_jenis_absen in ('S','S1','S2') THEN 'S' END kode_jenis_absen,
	       	d.jenis_penyakit,
	       	d.nama_rumah_sakit,
	       	isnull(kp.nama,'Tidak ada') as nama_pengganti,
	       	CONVERT(VARCHAR(20), h.tgl_mulai,105) AS tgl_mulai,
	       	CONVERT(VARCHAR(20), h.tgl_selesai,105) AS tgl_selesai,
	       	h.jml_hk,
	       	h.status,
	       	CONVERT(VARCHAR(20), h.created_at,105) AS tgl_simpan,
	       	h.created_by
	       	FROM
	       	tp_formulir_headers AS h
	       	LEFT JOIN ( SELECT tahun, bulan, nik, nama, namaunitbagian, namabagian FROM v_history_karyawan ) AS k ON k.nik = h.nik_simkar 
	       		AND k.tahun = YEAR ( h.tgl_mulai ) 
	       		AND k.bulan = MONTH ( h.tgl_mulai )
	       	LEFT JOIN (SELECT nik, nama, 	uu.namaunitbagian, bb.namabagian FROM karyawan_ptpn4 kk
	       		JOIN unitbagianmaster uu ON uu.kodeunitbagian = kk.unit
	       		JOIN tm_bagian bb ON bb.kodebagian = kk.kodebagian 
	       	) AS know ON know.nik = h.nik_simkar

	       	INNER JOIN tr_jenis_absens AS j ON j.kode = h.kode_jenis_absen
	       	LEFT JOIN (SELECT id_formulir_header, jenis_penyakit, nama_rumah_sakit FROM tp_formulir_details) as d ON h.id = d.id_formulir_header
	       	LEFT JOIN (SELECT nik, nama FROM karyawan_ptpn4) as kp ON kp.nik = h.nik_pengganti

	       	WHERE
	       	h.id = @id_formulir_header
       	END");
    }
}
