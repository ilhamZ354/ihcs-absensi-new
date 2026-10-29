<?php

use Illuminate\Database\Seeder;

class SpNewGenerateDetailAbsensiHarianNonmesin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_new_generate_detail_absensi_harian_nonmesin') IS NOT NULL) DROP PROCEDURE sp_new_generate_detail_absensi_harian_nonmesin");
        DB::statement("CREATE PROCEDURE sp_new_generate_detail_absensi_harian_nonmesin
			@tgl date
		AS
		BEGIN

			begin transaction

			begin try
					
				declare @tbl as table (	
					nik_simkar varchar(15) not null,
					kodeunitbagian varchar(5) not null,
					kodebagian varchar(15),
					kodejabatan varchar(15),
					nama varchar(150),
					tgl date,
					status varchar(10),
					karpim smallint,
					filter_kode varchar(5),
					created_by varchar(50)
				)
				
				insert into @tbl (nik_simkar, kodeunitbagian, kodebagian, kodejabatan, nama, tgl, status, karpim, filter_kode, created_by)

				-- START NON MACHINE
				select h.nik, h.unit, h.kodebagian, h.kodejabatan, h.nama, @tgl tgl,
					case when fh.kode_jenis_absen is not null then fh.kode_jenis_absen
							 when (ad.checkin_time is not null or ad.checkout_time is not null) or (ld.check_in is not null or ld.check_out is not null) or (mk.check_in is not null or mk.check_out is not null) then 'K'
							 when cb.tgl is not null then 'CB'
							 when lb.tgl is not null then 'L'
							 else 'K'
					end status
					, h.karpim
					, case when left(h.nik, 1) = 'H' then 'HONOR'
					 when left(h.nik, 1) not in('H', 'M', 'O', 'P') and karpim = 1 then '1'
					 when left(h.nik, 1) not in('H', 'M', 'O', 'P') and karpim = 0 then '0'
					 when left(h.nik, 1) in('M', 'O', 'P') then 'OTHER'
					 else null end filter_kode
					, 'Harapan' created_by
				from v_history_karyawan h
				left join tp_formulir_headers fh on fh.nik_simkar = h.nik and @tgl between fh.tgl_mulai and fh.tgl_selesai
				left join tr_absensi_details ad on ad.nik_simkar = h.nik and ad.tgl = @tgl
				left join v_att_log_dailies ld on ld.nik_simkar = h.nik and ld.tgl = @tgl
				left join v_absensi_mobile_kanpus mk on mk.nik_simkar = h.nik and mk.tgl = @tgl
				left join tr_libur_cuti_bersamas cb on cb.tgl = @tgl and cb.is_cb = 1
				left join tr_libur_cuti_bersamas lb on lb.tgl = @tgl and lb.is_cb = 0
				where h.tahun = year(@tgl)
				and h.bulan = month(@tgl)
				and h.unit not in ('00','01','02','03','04','62')
				and h.karpim in ('0', '1')
				and @tgl <= getdate()
				order by h.unit
				-- END OF NON MACHINE

				update b set b.status = a.status, b.karpim = a.karpim, b.filter_kode = a.filter_kode, b.updated_by = 'Harapan', b.updated_at = getdate()
				from @tbl a 
				inner join new_detail_absensi_nonmesins b on b.nik_simkar = a.nik_simkar and b.tgl = a.tgl

				insert into new_detail_absensi_nonmesins(nik_simkar, kodeunitbagian, kodebagian, kodejabatan, nama, tgl, status, karpim, filter_kode, created_by)
				select nik_simkar, kodeunitbagian, kodebagian, kodejabatan, nama, tgl, status, karpim, filter_kode, created_by 
				from @tbl a 
				where not exists (select * from new_detail_absensi_nonmesins b where b.nik_simkar = a.nik_simkar and b.tgl = a.tgl)
				
				commit transaction

			end try

			begin catch
			--   raiserror('Transaksi simpan detail absensi non mesin gagal', 16, 1)
				SELECT
							ERROR_NUMBER() AS ErrorNumber,
							ERROR_SEVERITY() AS ErrorSeverity,
							ERROR_STATE() AS ErrorState,
							ERROR_PROCEDURE() AS ErrorProcedure,
							ERROR_LINE() AS ErrorLine,
							ERROR_MESSAGE() AS ErrorMessage;
				rollback transaction
			end catch;

		END;");
    }
}
