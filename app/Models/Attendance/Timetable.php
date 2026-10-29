<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
	protected $connection = 'n4attendance';
	protected $table = 'timetables';

	protected $visible = [
		"employee_group_id",
		"dayname",
		"shift",
		"on_duty",
		"on_duty_min",
		"on_duty_max",
		"off_duty",
		"off_duty_min",
		"off_duty_max",
	];
}
