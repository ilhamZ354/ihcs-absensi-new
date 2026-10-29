<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\TpInsentifNormalisasi;

class NormalisasiInsentifImport implements ToCollection, WithHeadingRow
{
	private $tahun;
	private $bulan;

	public function __construct($tahun, $bulan)
	{
		$this->tahun = $tahun;
		$this->bulan = $bulan;
	}
	
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
        	if(substr($row['periode'], 0, 4) == $this->tahun && substr($row['periode'], 4, 2) == $this->bulan) {
        		$row['periode'] = intval($row['periode']);
        		$row['created_by'] = auth()->user()->getUser()->nama;
        		TpInsentifNormalisasi::create($row->toArray());
        	}
        }
    }
}
