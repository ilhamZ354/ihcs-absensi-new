<?php

namespace App\Models\Simkar;

use Illuminate\Database\Eloquent\Model;

class TmBagian extends Model
{
    protected $connection = 'simkar';
    protected $table = 'tm_bagian';
    protected $primaryKey = 'kodebagian';
    public $incrementing = false;

    public static function getTableName() {
		return (new self())->getTable();
	}
}
