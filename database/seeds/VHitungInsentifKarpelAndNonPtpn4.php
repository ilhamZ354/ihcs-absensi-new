<?php

use Illuminate\Database\Seeder;

class VHitungInsentifKarpelAndNonPtpn4 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('v_hitung_insentif_karpel') IS NOT NULL) DROP VIEW v_hitung_insentif_karpel");
        DB::statement("CREATE VIEW v_hitung_insentif_karpel
    	AS  
    		select a.*
			, a.hk * 4 [level_1] 
			, a.hk * 14 [level_2] 
			, a.hk * 24 [level_3] 
			, a.hk * 34 [level_4] 
			, a.hk * 35 [level_5] 
			, case 
					when akumulasi between 0 and (a.hk * 4) then 'level_1'
					when akumulasi between (a.hk * 4) and (a.hk * 14) then 'level_2'
					when akumulasi between (a.hk * 14) and (a.hk * 24) then 'level_3'
					when akumulasi between (a.hk * 24) and (a.hk * 34) then 'level_4'
				else 
					'level_5'
				end
				[level_final]
			from (
				select 
				 thn, bln, nik_simkar, nama_mesin, namabagian, namajabatan, mkg, namaunitbagian, isnull(sum(td),0) td, isnull(sum(cp),0) cp, isnull(sum(td)+sum(cp), 0) akumulasi
				-- , (select count(*) from tr_absensi_details ad where ad.pin = a.pin and ad.jam_masuk is not null and ad.jam_pulang is not null) [hk]
				, (select count(*) from tr_absensi_details ad where ad.pin = a.pin and ad.jam_masuk is not null and ad.jam_pulang is not null and year(ad.tgl) = a.thn and month(ad.tgl) = a.bln) [hk]
				from (
					select year(a.tgl) [thn], month(a.tgl) [bln], a.pin, p.nik_simkar, p.nama_mesin, hk.namabagian, hk.namajabatan, hk.mkg, hk.namaunitbagian
					, (cast(substring(cast(a.telat_datang as varchar), 1, 2) as int) * 60) + substring(cast(a.telat_datang as varchar), 4, 2) td
					, (cast(substring(cast(a.cepat_pulang  as varchar), 1, 2) as int) * 60) + substring(cast(a.cepat_pulang as varchar), 4, 2) cp
					from tr_absensi_details a
					join tp_pesertas p on p.pin = a.pin
					left join v_history_karyawan hk on hk.nik = p.nik_simkar and hk.tahun = year(a.tgl) and hk.bulan = month(a.tgl)
					where hk.karpim = 0
				) a
				group by thn, bln, a.pin, nik_simkar, nama_mesin, namabagian, namajabatan, mkg, namaunitbagian
			)a;");


        DB::statement("IF (OBJECT_ID('v_hitung_insentif_nonptpn4') IS NOT NULL) DROP VIEW v_hitung_insentif_nonptpn4");
        DB::statement("CREATE VIEW v_hitung_insentif_nonptpn4
    	AS 
			select a.*
			, a.hk * 4 [level_1] 
			, a.hk * 14 [level_2] 
			, a.hk * 24 [level_3] 
			, a.hk * 34 [level_4] 
			, a.hk * 35 [level_5] 
			, case 
					when akumulasi between 0 and (a.hk * 4) then 'level_1'
					when akumulasi between (a.hk * 4) and (a.hk * 14) then 'level_2'
					when akumulasi between (a.hk * 14) and (a.hk * 24) then 'level_3'
					when akumulasi between (a.hk * 24) and (a.hk * 34) then 'level_4'
				else 
					'level_5'
				end
				[level_final]
			from (
				select 
				 thn, bln, nik_simkar, nama_mesin, namabagian, namajabatan, mkg, namaunitbagian, isnull(sum(td),0) td, isnull(sum(cp),0) cp, isnull(sum(td)+sum(cp), 0) akumulasi
				-- , (select count(*) from tr_absensi_details ad where ad.pin = a.pin and ad.jam_masuk is not null and ad.jam_pulang is not null) [hk]
				, (select count(*) from tr_absensi_details ad where ad.pin = a.pin and ad.jam_masuk is not null and ad.jam_pulang is not null and year(ad.tgl) = a.thn and month(ad.tgl) = a.bln) [hk]
				from (
					select year(a.tgl) [thn], month(a.tgl) [bln], a.pin, p.nik_simkar, p.nama_mesin, hk.namabagian, hk.namajabatan, hk.mkg, hk.namaunitbagian
					, (cast(substring(cast(a.telat_datang as varchar), 1, 2) as int) * 60) + substring(cast(a.telat_datang as varchar), 4, 2) td
					, (cast(substring(cast(a.cepat_pulang  as varchar), 1, 2) as int) * 60) + substring(cast(a.cepat_pulang as varchar), 4, 2) cp
					from tr_absensi_details a
					join tp_pesertas p on p.pin = a.pin
					left join (
						select k.nik, b.namabagian, u.namaunitbagian, j.jabatan [namajabatan], null [mkg], k.karpim from tm_karyawan_nonptpn4 k
						join unitbagianmaster u on u.kodeunitbagian = k.unit
						join tm_bagian b on b.kodebagian = k.kodebagian
						join tm_jabatan j on j.kodejabatan = k.kodejabatan
					)hk on hk.nik = p.nik_simkar 
					where /*year(a.tgl) = 2018 and month(a.tgl) = 10 and */ hk.karpim = 0
				) a
				group by thn, bln, a.pin, nik_simkar, nama_mesin, namabagian, namajabatan, mkg, namaunitbagian
			)a;");
    }
}
