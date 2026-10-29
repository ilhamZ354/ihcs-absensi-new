<?php

use Illuminate\Database\Seeder;

class SpNewGenerateDetailBackdateAbsensiHarianHonor extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_new_generate_detail_backdate_absensi_harian_honor') IS NOT NULL) DROP PROCEDURE sp_new_generate_detail_backdate_absensi_harian_honor");
        DB::statement("CREATE PROCEDURE sp_new_generate_detail_backdate_absensi_harian_honor
			@tgl date
		AS
		BEGIN

			declare @tbl as table(
				nik_simkar varchar(15), 
				unit varchar(5), 
				kodebagian varchar(15), 
				kodejabatan varchar(15), 
				nama varchar(150), 
				tgl date, 
				checkin datetime, 
				checkout datetime, 
				status varchar(10),
				late int,
				early int,
				created_by varchar(50)
			)


			insert into @tbl(nik_simkar, unit, kodebagian, kodejabatan, nama, tgl, checkin, checkout, status, late, early, created_by)
			select nik, unit, kodebagian, kodejabatan, nama, tgl, checkin, checkout, status
			, case 
				when checkin is null and status in('K','KK') then 30
				when convert(time, checkin) > jam_masuk then DATEDIFF(minute, jam_masuk, convert(time, checkin))
				else null
			end terlambat
			, case 
				when checkout is null and getdate() > convert(datetime, convert(varchar(10), @tgl) + ' ' + convert(varchar(12), jam_pulang)) and status in('K','KK') then 30
				when convert(time, checkout) < jam_pulang then DATEDIFF(minute, convert(time, checkout), jam_pulang)
				else null
			end cepat_pulang
			, 'Harapan' created_by from (
			select h.nik, h.unit, h.kodebagian, h.kodejabatan, h.nama, @tgl tgl, 	
				case when fh.kode_jenis_absen is not null then null
						 when ad.checkin_time is not null then ad.checkin_time
						 when ld.check_in is not null then ld.check_in
						 when mk.check_in is not null then mk.check_in 
						 when cb.tgl is not null then null
						 when lb.tgl is not null then null
						 when tm.dayname is null then null		 
						 else null
				end checkin,
				case when fh.kode_jenis_absen is not null then null
						 when ad.checkout_time is not null then ad.checkout_time
						 when ld.check_out is not null then ld.check_out
						 when mk.check_out is not null then mk.check_out
						 when cb.tgl is not null then null
						 when lb.tgl is not null then null
						 when tm.dayname is null then null		 
						 else null
				end checkout,

				case when fh.kode_jenis_absen is not null then fh.kode_jenis_absen
						 when tm.dayname is null and fh.kode_jenis_absen = 'K' then 'K'			 
						 when cb.tgl is not null then 'CB'
						 when lb.tgl is not null then 'L'
						 when tm.dayname is null then 'L'
						 when (ad.checkin_time is not null or ad.checkout_time is not null) or (ld.check_in is not null or ld.check_out is not null) or (mk.check_in is not null or mk.check_out is not null) then 'K'
						 else 'N/A'
				end status
				, tm.jam_masuk
				, tm.jam_pulang
			from v_history_karyawan h
			left join tp_formulir_headers fh on fh.nik_simkar = h.nik and @tgl between fh.tgl_mulai and fh.tgl_selesai
			left join (select ad.nik_simkar, ad.tgl 
									, case when tgl is not null or ad.checkin_time is not null then convert(varchar(10), tgl) + ' ' + convert(varchar(12), ad.checkin_time) else null end [checkin_time]
									, case when tgl is not null or ad.checkout_time is not null then convert(varchar(10), tgl) + ' ' + convert(varchar(12), ad.checkout_time) else null end [checkout_time]
									from tr_absensi_details ad ) ad on ad.nik_simkar = h.nik and ad.tgl = @tgl

			left join v_att_log_dailies ld on ld.nik_simkar = h.nik and ld.tgl = @tgl
			left join v_absensi_mobile_kanpus mk on mk.nik_simkar = h.nik and mk.tgl = @tgl
			left join tr_libur_cuti_bersamas cb on cb.tgl = @tgl and cb.is_cb = 1
			left join tr_libur_cuti_bersamas lb on lb.tgl = @tgl and lb.is_cb = 0
			left join tp_timetables tm on tm.kode_unit = h.unit and tm.status = 1 and dayname = datename(dw, @tgl)
			where h.tahun = year(getdate())
			and h.bulan = month(getdate())
			and h.unit in (select kode_unit from tp_unit_mesins where status = 1)
			and h.karpim in ('0', '1')
			and h.kodejabatan not in (select kodejabatan from tm_jabatan where isactive = 'Y' and jabatan like '%satpam%')
			and @tgl <= getdate()
			and left(h.nik, 1) = 'H'
			) a

			update b set b.checkin = a.checkin, b.checkout = a.checkout, b.status = a.status, b.late = a.late, b.early = a.early, b.updated_by = 'Harapan', b.updated_at = getdate()
			from @tbl a
			inner join new_detail_absensi_mesins b on a.nik_simkar = b.nik_simkar and a.tgl = b.tgl

			insert into new_detail_absensi_mesins (nik_simkar, unit, kodebagian, kodejabatan, nama, tgl, checkin, checkout, status, late, early, created_by)
			select nik_simkar, unit, kodebagian, kodejabatan, nama, tgl, checkin, checkout, status, late, early, created_by
			from @tbl a
			where not exists (select * from new_detail_absensi_mesins b where a.nik_simkar = b.nik_simkar and a.tgl = b.tgl)

		END;");
    }
}
