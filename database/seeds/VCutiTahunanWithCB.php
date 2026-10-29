<?php

use Illuminate\Database\Seeder;

class VCutiTahunanWithCB extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('v_cuti_tahunan_with_cb') IS NOT NULL) DROP VIEW v_cuti_tahunan_with_cb");
        DB::statement("CREATE VIEW v_cuti_tahunan_with_cb
        	AS
		    
            /*select ct.id, ct.nik_simkar, ct.kuota, ct.terpakai + count(l.id) [terpakai], ct.tgl_mulai, ct.tgl_berakhir, ct.status
            from tr_kuota_cuti_tahunans ct
            left join (select * from tr_libur_cuti_bersamas where is_cb = 1) l on l.tgl between ct.tgl_mulai and ct.tgl_berakhir
            group by ct.id, ct.nik_simkar, ct.kuota, ct.terpakai, ct.tgl_mulai, ct.tgl_berakhir, ct.status;*/

            select ct.id, ct.nik_simkar, ct.kuota, (ct.terpakai + count(l.id)) - count(k_on_cb.nik_simkar) [terpakai], ct.tgl_mulai, ct.tgl_berakhir, ct.status
            from tr_kuota_cuti_tahunans ct
            left join (select * from tr_libur_cuti_bersamas where is_cb = 1) l on l.tgl between ct.tgl_mulai and ct.tgl_berakhir and l.tgl <= getdate()
            left join (
                select h.nik_simkar, lcb.tgl from tr_libur_cuti_bersamas lcb
                join tp_formulir_headers h on lcb.tgl between h.tgl_mulai and h.tgl_selesai
                where h.kode_jenis_absen='K' and lcb.is_cb = 1
            ) k_on_cb on k_on_cb.nik_simkar = ct.nik_simkar and k_on_cb.tgl between ct.tgl_mulai and ct.tgl_berakhir
            group by ct.id, ct.nik_simkar, ct.kuota, ct.terpakai, ct.tgl_mulai, ct.tgl_berakhir, ct.status,k_on_cb.nik_simkar;");
    }
}
