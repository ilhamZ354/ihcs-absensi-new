<?php

namespace App\Models\Simkar;

use Illuminate\Database\Eloquent\Model;

class UnitBagianMaster extends Model
{
    protected $connection = 'simkar';
    protected $table = 'unitbagianmaster';
    protected $primaryKey = 'kodeunitbagian';
    public $incrementing = false;
}
