<?php

namespace App\Services\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WFHExport implements FromView, ShouldAutoSize, WithColumnFormatting
{
    private $collection;
    private $tahun;
    private $bulan;
    private $jenis_laporan;

    public function __construct($collection, $tahun, $bulan, $jenis_laporan)
    {
        $this->collection = $collection;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->jenis_laporan = $jenis_laporan;
    }
    
    public function view(): View
    {
        $date_start = date('Y-m-d', strtotime($this->tahun.'-'.toNumberOfMonth($this->bulan).'-01'));
        $date_end = date('Y-m-d', strtotime($date_start. "+1 month"));
        $max_date = date_diff(date_create($date_start), date_create($date_end))->format("%a");

        return view('desktop.pages.laporan.wfh.report', [
            'rows' => $this->collection,
            'max_date' => $max_date,
            'filter' => (object) ['tahun' => $this->tahun, 'bulan' => $this->bulan, 'jenis_laporan' => $this->jenis_laporan],
        ]);
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT
        ];
    }
}