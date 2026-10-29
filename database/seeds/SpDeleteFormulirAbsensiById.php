<?php

use Illuminate\Database\Seeder;

class SpDeleteFormulirAbsensiById extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('sp_delete_formulir_absensi_by_id') IS NOT NULL) DROP PROCEDURE sp_delete_formulir_absensi_by_id");
        DB::statement("CREATE PROCEDURE sp_delete_formulir_absensi_by_id
			@id_formulir_header int
			, @username varchar(50)
			, @aktifitas varchar(250)
		AS
		BEGIN

			DECLARE @ID_KUOTA INT, @JENIS VARCHAR(4), @HK SMALLINT

			select @ID_KUOTA = id_kuota, @JENIS = kode_jenis_absen, @HK = jml_hk from tp_formulir_headers fh where id = @id_formulir_header
			BEGIN TRY
				BEGIN TRANSACTION DELETEFORMULIR		
					IF (@JENIS = 'CT')
						UPDATE tr_kuota_cuti_tahunans SET 
							terpakai = (terpakai - @HK) 
							, updated_by = @username
							, updated_at = getdate()
						where id = @ID_KUOTA		

					IF (@JENIS = 'CP')
						UPDATE tr_kuota_cuti_panjangs SET 
							terpakai = (terpakai - @HK) 
							, updated_by = @username
							, updated_at = getdate()
						where id = @ID_KUOTA
						--select terpakai - @HK, terpakai FROM tr_kuota_cuti_tahunans where id = @ID_KUOTA

					IF (@JENIS IN ('S', 'S1', 'S2'))
						DELETE FROM tp_formulir_details where id_formulir_header = @id_formulir_header

					DELETE tp_formulir_headers WHERE id = @id_formulir_header

					
					insert into tp_log_aktifitas (username, aktifitas, created_by, created_at)
					values (@username, @aktifitas, 'Delete formulir absensi', getdate())
					
					COMMIT TRANSACTION DELETEFORMULIR		
				END TRY
				BEGIN CATCH 
				IF (@@TRANCOUNT > 0)
				BEGIN
					ROLLBACK TRANSACTION DELETEFORMULIR
				END 
			END CATCH
		END;");
    }
}
