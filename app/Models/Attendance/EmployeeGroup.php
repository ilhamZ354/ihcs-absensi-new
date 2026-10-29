<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmployeeGroup extends Model
{
    protected $connection = 'n4attendance';
    protected $table = 'employee_groups';

	protected $fillable = [
        'location_id',
        'name',
        'is_security',
    ];
    protected $hidden = [
		"created_at",
		"updated_at",
		"deleted_at",
	];

    public function location()
    {
    	return $this->belongsTo(Location::class);
    }

    public function timetables()
    {
    	return $this->hasMany(Timetable::class);
    }

    public function timetable()
    {
    	return $this->hasOne(Timetable::class)->where('dayname', Carbon::now()->isoFormat('dddd'));
    }
}
