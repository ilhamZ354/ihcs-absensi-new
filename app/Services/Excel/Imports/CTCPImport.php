<?php

namespace App\Services\Excel\Imports;

use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\TrFileUploadCtcp;
use App\Models\TempFileParse;
use App\Models\TpLogAktifitas;
use App\Models\TrNotification;
use Log;
use DB;

class CTCPImport implements ToCollection
{
	use Importable;

    protected $tahun;
    protected $username;
    protected $id_upload;

    public function __construct($id_upload, $username, $tahun)
    {
        $this->tahun = $tahun;
        $this->username = $username;
        $this->id_upload = $id_upload;
    }
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try {
            foreach($collection as $i => $row) {
                if($i > 0) {
                    TempFileParse::insert([
                        'tahun'=>$this->tahun,
                        'id_upload'=>$this->id_upload,
                        'no'=>$row[0],
                        'th_row'=> $row[1],
                        'pj_row'=> empty($row[2]) ? null : $row[2],
                        'nik'=> $row[3],
                        'nik_sap'=> $row[4],
                        'nama'=> $row[5],
                        'tipe_karyawan'=> $row[6],
                        'tanggal_bekerja'=> date('Y-m-d', strtotime($row[7])),
                        'namaunit'=> $row[8],
                        'namabagian'=> $row[9],
                        'namajabatan'=> $row[10],
                        'tgl_mulai_tahunan'=> empty($row[11]) || is_null($row[11]) ? null : date('Y-m-d', strtotime($row[11])),
                        'tgl_berakhir_tahunan'=> empty($row[12]) || is_null($row[12]) ? null : date('Y-m-d', strtotime($row[12])),
                        'kuota_cuti_tahunan'=> $row[13],
                        'tgl_mulai_panjang'=> empty($row[14]) || is_null($row[14]) ? null : date('Y-m-d', strtotime($row[14])),
                        'tgl_berakhir_panjang'=> empty($row[15]) || is_null($row[15]) ? null : date('Y-m-d', strtotime($row[15])),
                        'kuota_cuti_panjang'=> $row[16],
                        'created_by'=>'AbsensiParser',
                        'created_at'=>date('Y-m-d'),
                    ]);                    
                }
            }
        } catch (QueryException $e) {
            $this->rollBackUpload();
            Log::error(__METHOD__ . " >>> Import File CTCP Gagal.");
            Log::error(__METHOD__ . " >>> " . $e->getMessage());                    
        } catch (Exception $e) {
            Log::error(__METHOD__ . " >>> Runtime Error when CTCP file is importing to system.");
            $this->rollBackUpload();
            Log::error(__METHOD__ . " >>> " . $e->getMessage());
        }
    }

    private function rollBackUpload()
    {
        DB::beginTransaction();
        try {
            TrFileUploadCtcp::where('id', $this->id_upload)->delete();
            TempFileParse::where('id_upload', $this->id_upload)->delete();
            DB::commit(); 
            Log::info(__METHOD__ . " >>> Berhasil menghapus TrFileUploadCtcp dengan id = {$this->id_upload}");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error("Gagal menghapus TrFileUploadCtcp dengan id = {$this->id_upload}");
            Log::error(__METHOD__ . " >>> " . $e->getMessage());
        }
    }
}
