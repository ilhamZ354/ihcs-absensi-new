<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use App\Models\Simkar\KaryawanPtpn4;
use App\Models\Simkar\KaryawanNonPtpn4;

class Employee extends Model
{
	// use Notifiable, HasApiTokens;
    protected $connection = 'n4attendance';

    protected $table = 'employees';
    protected $primaryKey = 'nik';
    public $keyType = 'string';

	// protected $guard = 'employee';

	protected $fillable = ['name','nick_name','nik','username','password','phone_number', 'approver_nik', 'is_security', 'employee_group_id', 'imei', 'activated_at'];

	protected $hidden = [
		"password",
		"created_at",
		"updated_at",
		"deleted_at",
	];

	public function simkar()
	{
		if(in_array(substr($this->nik, 0, 1), ['H','M','O','P']))  {
			return $this->belongsTo(KaryawanNonPtpn4::class, 'nik', 'nik');
		} else {
			return $this->belongsTo(KaryawanPtpn4::class, 'nik', 'nik');
		}
	}	

	public function employeeGroup()
	{
		return $this->belongsTo(EmployeeGroup::class);
	}

	public function tokens()
	{
		return $this->hasMany(Token::class);
	}

	public function attendances()
	{
		return $this->hasMany(Attendance::class, 'nik', 'nik');
	}

	public function attendanceToday()
	{
		return $this->hasOne(Attendance::class, 'nik', 'nik')->where('date', date('Y-m-d'))
		->select(['id', 'nik','name','date','check_in','check_out']);
	}
}
