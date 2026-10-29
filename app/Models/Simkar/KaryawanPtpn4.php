<?php

namespace App\Models\Simkar;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance\Location;

class KaryawanPtpn4 extends Model
{
    protected $connection = 'simkar';
    protected $table = 'karyawan_ptpn4';
    protected $primaryKey = 'nik';
    public $incrementing = false;

    protected $visible =   [
		'nik',
		'nik_sap',
		'nama_karyawan',
		'nama',
		'jenis_kelamin',
		'tipe_karyawan',
		'karpim',
		'unit',
		'kodebagian',
		'kodejabatan',
		'status'
	];


    public function unitkerja()
    {
    	return $this->belongsTo(UnitBagianMaster::class, 'unit', 'kodeunitbagian');
    }

    public function bagianx()
    {
    	return $this->belongsTo(TmBagian::class, 'kodebagian');
    }

    public function jabatan()
    {
    	return $this->belongsTo(TmJabatan::class, 'kodejabatan', 'kodejabatan');
    }

    public function location()
    {
    	return $this->belongsTo(Location::class, 'kodeunitbagian', 'kode_unit');
    }
}
