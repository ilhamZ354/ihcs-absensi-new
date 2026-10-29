<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	protected $connection = 'n4attendance';
	protected $table = 'locations';

	protected $fillable = ['unit', 'name', 'coordinates'];
	protected $visible = [
		"unit",
		"name",
		"coordinates",
	];
}
