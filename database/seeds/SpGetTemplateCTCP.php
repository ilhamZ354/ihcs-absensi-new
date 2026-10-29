<?php

use Illuminate\Database\Seeder;

class SpGetTemplateCTCP extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_get_template_ctcp') IS NOT NULL) DROP PROCEDURE sp_get_template_ctcp");    	
        DB::statement("CREATE PROCEDURE [dbo].[sp_get_template_ctcp] @KODEUNIT VARCHAR ( 10 ), @KODEBAGIAN VARCHAR ( 15 ) AS BEGIN
		DECLARE
			@BASEQUERY NVARCHAR ( MAX ), @WHEREBAGIAN NVARCHAR ( MAX ) 
			SET @BASEQUERY = 'Select ROW_NUMBER() OVER(ORDER BY k.unit, k.kodebagian, k.karpim DESC, k.nama ASC) AS rownum,
			th.id as th_row,
			pj.id as pj_row,
			k.nik,
			k.nik_sap,
			k.nama,
			CAST (k.karpim AS VARCHAR) AS tipe_karyawan,
			CONVERT(VARCHAR(20), k.tanggal_bekerja,105) AS tanggal_bekerja,
			u.namaunitbagian AS namaunit,
			b.namabagian AS namabagian,
			j.jabatan AS namajabatan,
			CONVERT(VARCHAR(20), th.tgl_mulai,105) AS tgl_mulai_tahunan,
			CONVERT(VARCHAR(20), th.tgl_berakhir,105) AS tgl_berakhir_tahunan,
			0 AS kuota_cuti_tahunan,
			CONVERT(VARCHAR(20), pj.tgl_mulai,105) AS tgl_mulai_panjang,
			CONVERT(VARCHAR(20), pj.tgl_berakhir, 105) AS tgl_berakhir_panjang,
			0 AS kuota_cuti_panjang
		FROM
			karyawan_ptpn4 AS k
			INNER JOIN unitbagianmaster AS u ON k.unit = u.kodeunitbagian
			INNER JOIN tm_bagian AS b ON k.kodebagian = b.kodebagian
			INNER JOIN tm_jabatan AS j ON k.kodejabatan = j.kodejabatan
			LEFT JOIN tp_pesertas AS p ON k.nik = p.nik_simkar
			LEFT JOIN dbo.tr_kuota_cuti_tahunans AS th ON th.nik_simkar = k.nik
			LEFT JOIN dbo.tr_kuota_cuti_panjangs AS pj ON pj.nik_simkar = k.nik
		WHERE
			k.status = ''01'' 
			AND  th.tgl_mulai <= getdate() and th.tgl_berakhir >= dateadd(day, -1, getdate())
			'
		IF
			( @KODEUNIT = '00' AND @KODEBAGIAN IS NOT NULL ) BEGIN
			SET @WHEREBAGIAN = ' AND k.KODEBAGIAN = ''' +@KODEBAGIAN + ''' AND k.UNIT = ''' +@KODEUNIT + '''' 
		END ELSE
		IF
			( @KODEUNIT = '68' ) BEGIN
				
				SET @WHEREBAGIAN = ' AND k.UNIT IN (''51'',''52'',''55'',''68'')' 
		END ELSE
		IF
			( @KODEUNIT = '66' ) BEGIN
				
				SET @WHEREBAGIAN = ' AND k.UNIT IN (''66'',''24'',''43'',''59'')' 
			END ELSE BEGIN
				
				SET @WHEREBAGIAN = ' AND k.UNIT = ''' +@KODEUNIT + '''' 
			END 
				SET @BASEQUERY = @BASEQUERY + @WHEREBAGIAN + 'ORDER BY k.karpim DESC, k.nama ASC'
				--SET @BASEQUERY = @BASEQUERY + 'ORDER BY k.unit, k.kodebagian, k.karpim DESC, k.nama ASC'--SET @BASEQUERY = @BASEQUERY + ' AND k.KARPIM = ''0'' AND A.STATUS IN (''01'',''38'') and e.status =''1'''-- 	SELECT (@BASEQUERY)\
				
			EXECUTE ( @BASEQUERY ) 
		END;");
    }
}
