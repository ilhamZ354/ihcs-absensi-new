<?php

namespace App\Services\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\TrAbsensiDetail;

class AbsensiImport implements ToCollection,WithBatchInserts, WithChunkReading
{
	public function __construct()
	{
		set_time_limit(1200); 
	}
	
    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) 
        {
        	if($i > 0 && date('Y-m', strtotime($row[3])) == '2018-10' && (!empty($this->getTime($row[6])) || !empty($this->getTime($row[7]))))
	        	TrAbsensiDetail::insert([
	        		'pin' => $row[0],
	        		'nik_simkar' => $row[1],
	        		// 'nama_mesin' => $row['Nama'],
	        		'tgl' => date('Y-m-d', strtotime($row[3])),
	        		// 'jam_masuk' => $row['Jam Masuk'],
	        		// 'jam_pulang' => $row['Jam Pulang'],
	        		'checkin_time' => $this->getTime($row[6]),
	        		'checkout_time' => $this->getTime($row[7]),
	        		'telat_datang' => $this->getLateOrFast($row[8]),
	        		'cepat_pulang' => $this->getLateOrFast($row[9]),
	        		// 'Absent' => $row['Absent'],
	        		'created_by' => 'AbsensiImport',
	        	]);
        }
    }

     public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getTime($rawText) //12/30/1899 17:30
    {
    	if(empty($rawText))
    		return null;
    	else
    		return date('H:i:s', strtotime(substr($rawText, -5)));
    }

    public function getLateOrFast($rawText) //12/30/1899 17:30
    {
    	if(empty($rawText))
    		return null;
    	else
    		return $rawText;
    }

    public function __destruct()
    {
    	set_time_limit(120); 
    }
}