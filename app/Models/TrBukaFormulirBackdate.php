<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrBukaFormulirBackdate extends Model
{
    protected $fillable = [
    	'kodeunitbagian',
    	'kodebagian',
    	'tgl_ubah_mulai',
    	'tgl_ubah_selesai',
    	'dibuka_dari',
    	'dibuka_sampai',
    	'created_by',
    	'updated_by',
    	'status',
    	'keterangan',
    ];
}
