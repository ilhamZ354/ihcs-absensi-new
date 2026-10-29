<?php

namespace App\Services\Excel\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BKMExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $viewPath;
	private $data;

	public function __construct($viewPath, $data)
	{
        $this->viewPath = $viewPath;
		$this->data = $data;
	}

    public function view(): View
    {
        return view($this->viewPath, $this->data);
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'S' => NumberFormat::FORMAT_TEXT,
            'U' => NumberFormat::FORMAT_TEXT,
        ];
    }

}
