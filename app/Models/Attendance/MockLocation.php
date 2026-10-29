<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;

class MockLocation extends Model
{
	protected $connection = 'n4attendance';
	protected $table = 'mock_locations';

    protected $fillable = ['employee_id'];

    public function employee()
    {
    	return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
