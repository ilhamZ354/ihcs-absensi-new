<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
	protected $connection = 'n4attendance';
	protected $table = 'attendances';

    protected $fillable = [
    	'nik',
    	'name',
    	'date',
    	'check_in',
    	'check_out',
    	'coordinate_in',
    	'coordinate_out',
    ];

    protected $casts  = [
        'check_in' => 'date:H:i:s',
        'check_out' => 'date:H:i:s',
    ];
}
