<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempFileParse extends Model
{
    protected $fillable = [
        'no',
        'id_upload',
        'tahun',
    	'th_row',
    	'pj_row',        
    	'nik',
    	'nik_sap',
        'nama',
        'tipe_karyawan',
        'tanggal_bekerja',
        'namaunit',
        'namabagian',
        'namajabatan',
        'tgl_mulai_tahunan',
        'tgl_berakhir_tahunan',
        'kuota_cuti_tahunan',
        'tgl_mulai_panjang',
        'tgl_berakhir_panjang',
    	'kuota_cuti_panjang'
    ];
}
