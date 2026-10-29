<?php

namespace App\Services\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AsistensiGpuExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings
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
    		'D' => NumberFormat::FORMAT_TEXT
    	];
    }

    public function headings(): array
    {
    	return [
    		'NIK SIMKAR',
    		'NIK SAP',
    		'NAMA',
    		'KODE UNIT',
    		'KODE BAGIAN',
    		'KODE JABATAN',
    		'TANGGAL',
    		'CHECKIN',
    		'CHECKOUT',
    		'STATUS',
    		'TERLAMBAT',
    		'CEPAT PULANG',
    	];
    }
}