<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Simkar\KaryawanPtpn4;
use App\Models\Simkar\KaryawanNonPtpn4;

class TpInsentifNormalisasi extends Model
{
	public $table = 'tp_insentif_normalisasi';

	protected $guard = [
		'created_at',
		'updated_at'
	];
	
	protected $fillable = [
		'nik',
		'periode',
		'keterangan',
		'created_by',
	];

	public function karyawan()
	{
		return $this->belongsTo(KaryawanPtpn4::class, 'nik', 'nik')->select('nik', 'nama', 'unit', 'kodebagian', 'kodejabatan');
	}

	public function nonptpn4()
	{
		return $this->belongsTo(KaryawanNonPtpn4::class, 'nik', 'nik')->select('nik', 'nama_karyawan', 'tipe_karyawan', 'unit', 'kodebagian', 'kodejabatan');
	}
}
