<?php

namespace App\Services\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KehadiranIndividuTahunanExport implements FromCollection, ShouldAutoSize, WithColumnFormatting
{
	private $collection;

	public function __construct($collection)
	{
		$this->collection = $collection;
	}
	
	/**
    * @return \Illuminate\Support\Collection
    */
	public function collection()
	{
		return $this->collection;
	}

    /**
     * @return array
     */
    public function columnFormats(): array
    {
    	return [
    		'A' => NumberFormat::FORMAT_TEXT
    	];
    }
}