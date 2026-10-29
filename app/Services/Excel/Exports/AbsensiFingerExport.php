<?php

namespace App\Services\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AbsensiFingerExport implements FromCollection, ShouldAutoSize, WithColumnFormatting
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
            'A' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_DATE_XLSX15,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_DATE_TIME3,
            'I' => NumberFormat::FORMAT_DATE_TIME3
        ];
    }
}
