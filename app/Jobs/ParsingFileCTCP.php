<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\TrFileUploadCtcp;
use App\Models\TempFileParse;
use App\Models\TpLogAktifitas;
use App\Models\TrNotification;
use DB;
use Log;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Excel\Imports\CTCPImport;

class ParsingFileCTCP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id_upload;
    protected $username;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id_upload, $username)
    {
        $this->id_upload = $id_upload;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // open path and parse to DB        
        $file = TrFileUploadCtcp::find($this->id_upload);
        (new CTCPImport($this->id_upload, $this->username, $file->tahun))->import($file->path, 'local','Xls');

        if(!$this->is_import_success()) {
            $message = "Upload Anda gagal: Sistem tidak dapat memroses file yang Anda upload karena isi file tidak sesuai dengan format yang ditentukan. Mohon segera diperbaiki atau hubungi Administrator jika mengalami kendala.";
            $this->rollBackUploadAndWriteNotification($message);
        } else if(!$this->is_row_count_match($file->kode_unit, $file->kode_bagian)) {
            $count = $this->getSystemRowsCount($file->kode_unit, $file->kode_bagian);

            $message = "Upload Anda gagal: jumlah karyawan dalam file yang Anda upload tidak sesuai, seharusnya jumlah karyawan adalah {$count}. Mohon segera diperbaiki."; 

            $this->rollBackUploadAndWriteNotification($message);
            
        } else if($this->is_overflow()) {
            $detail = "";

            if(!empty($this->getOverflowDetail()) || !is_null($this->getOverflowDetail())) {
                $detail = "(A/n: " . $this->getOverflowDetail() . ")";
            }

            $message = "Upload Anda gagal: Kuota cuti {$detail} dalam file yang telah Anda upload melampaui batas, mohon untuk memeriksa kembali nilai kuota cuti pada file sebelum diupload ulang."; 
            
            $this->rollBackUploadAndWriteNotification($message);
        } else if($this->is_null_value()) {
            $message = 'Upload Anda gagal: Kuota cuti dalam file yang telah Anda upload memiliki field yang kosong. Seharusnya anda mengisi nilai 0 (Nol) pada field tersebut. Mohon segera diperbaiki.'; 

            $this->rollBackUploadAndWriteNotification($message);
        } else {
            DB::statement("EXEC SP_PARSE_CTCP ?", [$this->username]);
            
            $this->writeNotification('Upload Cuti Berhasil: Terima kasih sudah mengupload.');
            
            $this->flagAsSuccess();

            Log::info('Berhasil menjalankan SP_PARSE_CTCP');
        }
        
    }

    private function flagAsSuccess() 
    {
        DB::beginTransaction();
        try {
            TrFileUploadCtcp::where('id', $this->id_upload)
            ->update([
                'verified_status' => 1,
                'updated_by' => "ParsingFileCTCP_JOB",
                'updated_at' => now(),
            ]);
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error(__METHOD__ . " >> Gagal menandai id_upload:{$this->id_upload} telah sukses");
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " >> Sistem error saat menandai id_upload:{$this->id_upload} telah sukses");
            Log::error($e->getMessage());
        }
        
    }

    public function is_import_success():bool
    {
        return TempFileParse::where('id_upload', $this->id_upload)->count() > 0;
    }

    public function is_row_count_match($unit, $bagian):bool
    {
        $fileRows = TempFileParse::where('id_upload', $this->id_upload)->count();
        $systemRows = $this->getSystemRowsCount($unit, $bagian);
        Log::info(">>> fileRows vs systemRows = {$fileRows}:{$systemRows}");
        return $fileRows == $systemRows;
    }

    private function getSystemRowsCount($unit, $bagian)
    {
        return collect(DB::select("sp_get_count_template_ctcp ?,?", [$unit, $bagian]))->first()->count;
    }

    public function is_overflow():bool
    {
        $tahunan_overflows = TempFileParse::where('kuota_cuti_tahunan', '>', 12)->count();
        $panjang_overflows = TempFileParse::where('kuota_cuti_panjang', '>', 45)->count();
        return $tahunan_overflows > 0 || $panjang_overflows > 0;
    }

    private function getOverflowDetail()
    {
        $cts = TempFileParse::select('nama')
        ->where('id_upload', $this->id_upload)
        ->where(function ($query) {
            $query->where('kuota_cuti_tahunan', '>', 12)
            ->orWhere('kuota_cuti_panjang', '>', 45);
        })
        ->get();

        $result = '';
        foreach($cts as $i => $ct) {
            if($i == 0)
                $result .= " " .$ct->nama;
            else
                $result .= ", " .$ct->nama;
        }
        return $result;
    }

    public function is_null_value():bool
    {
        $tahunan_null = TempFileParse::whereNull('kuota_cuti_tahunan')->count();
        $panjang_null = TempFileParse::whereNull('kuota_cuti_panjang')->count();
        return $tahunan_null > 0 || $panjang_null > 0;
    }

    private function writeLog($message)
    {
        try {
            return TpLogAktifitas::insertGetId([
                'username' => $this->username,
                'aktifitas' => $message,
                'created_by' => 'ParsingFileCTCP'
            ]);
        } catch (QueryException $e) {
            Log::error(__METHOD__ . " >>> Error saat menulis log aktifitas ke dalam DB.");
            Log::error($e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " >>> Runtime Error saat menulis log aktifitas.");
            Log::error($e->getMessage());
            return null;
        }
    }

    private function writeNotification($message)
    {
        $id_log = $this->writeLog('['.date('d-m-Y H:i:s') . '] - ' . $message);
        if($id_log != null) {
            try {                
                TrNotification::insert([
                    'id_log_aktifitas' => $id_log,
                    'username_target' => $this->username,
                ]);                    
                Log::error(__METHOD__ . " >>> " . $message);
            } catch (QueryException $e) {
                Log::error(__METHOD__ . " >>> Error saat menulis notifikasi kegagalan parse upload CTCP ke dalam DB.");
                Log::error($e->getMessage());
            } catch (\Exception $e) {
                Log::error(__METHOD__ . " >>> Runtime Error saat menulis notifikasi kegagalan parse upload CTCP.");
                Log::error($e->getMessage());
            }
        }
    }

    private function rollBackUploadAndWriteNotification($message)
    {
        DB::beginTransaction();

        try {
            TrFileUploadCtcp::where('id', $this->id_upload)->delete();
            DB::commit(); 
            Log::info(__METHOD__ . " >>> Berhasil menghapus TrFileUploadCtcp dengan id = {$this->id_upload}");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error("Gagal menghapus TrFileUploadCtcp dengan id = {$this->id_upload}");
            Log::error(__METHOD__ . " >>> " . $e->getMessage());
        }

        $this->writeNotification($message);

        DB::statement("truncate table temp_file_parses");
        Log::error("Job berhasil membersihakn temp_file_parses");
    }
}
