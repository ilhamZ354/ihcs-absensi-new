<?php

namespace App\Models\Simkar;

use Illuminate\Database\Eloquent\Model;

class TmJabatan extends Model
{
    protected $connection = 'simkar';
    protected $table = 'tm_jabatan';
    protected $primaryKey = 'kodejabatan';
    public $incrementing = false;
}
