<?php

namespace App\Services\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\Exportable;

class CTCPExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings
{
    use Exportable;

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

    public function headings(): array
    {
        return [
            'NO',
            'TH ROW',
            'PJ ROW',
            'NIK',
            'NIK SAP',
            'NAMA',
            'KARPIM',
            'TGL MASUK KERJA',
            'NAMA UNIT',
            'NAMA BAGIAN',
            'NAMA JABATAN',
            'TGL MULAI CUTI TAHUNAN',
            'TGL BERAKHIR CUTI TAHUNAN',
            'KUOTA CUTI TAHUNAN',
            'TGL MULAI CUTI PANJANG',
            'TGL BERAKHIR CUTI PANJANG',
            'KUOTA CUTI PANJANG'
        ];
    }

}
