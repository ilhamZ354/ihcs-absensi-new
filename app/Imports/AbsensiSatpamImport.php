<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use App\Models\TrAbsensiDetail;
use DB;
use Log;

class AbsensiSatpamImport implements ToCollection, WithCustomCsvSettings, WithHeadingRow
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}
	
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {

    	foreach ($rows as $row) 
    	{
    		$data = [
    			'pin' => $row['ac_no'], 
    			'nik_simkar' => substr($row['no'], 0, 1) == "'" ? substr($row['no'], 1) : $row['no'],
    			'tgl' => $this->_fixDate($row['date']), 
    			'jam_masuk' => $row['on_duty'], 
    			'jam_pulang' => $row['off_duty'], 
    			'checkin_time' => $row['clock_in'], 
    			'checkout_time' => $row['clock_out'], 
    			'created_by' => AbsensiSatpamImport::class, 
    			'id_log_upload_absensi' => $this->id, 
    		];
    		DB::table('tr_absensi_details_raw')->insert($data);
    	}

        Log::info("Sebelum menjalankan sp_new_parsing_absensi_satpam_upload_by_id");
        DB::statement("exec sp_new_parsing_absensi_satpam_upload_by_id ?", [$this->id]);
        Log::info("Setelah menjalankan sp_new_parsing_absensi_satpam_upload_by_id");
    }

    private function _fixDate($date) {
        $dt = explode('/', $date);
        return $dt[2].'-'.$dt[1].'-'.$dt[0];
    }

    public function getCsvSettings(): array
    {
    	return [
    		'delimiter' => ';'
    	];
    }
}
