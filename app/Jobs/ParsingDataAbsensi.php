<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\TpLogAktifitas;
use App\Models\TpLogUploadAbsensi;
use App\Imports\AbsensiSatpamImport;
use DB;
use Log;
use Auth;
use Excel;
use Storage;

class ParsingDataAbsensi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $executable = 'assets/library/parseabsensi.exe';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $upload = TpLogUploadAbsensi::find($this->id);
        Excel::import(new AbsensiSatpamImport($this->id), $upload->file_absensi, 'local', \Maatwebsite\Excel\Excel::CSV);
        $this->writeLog('ParseAbsensiLog:: AbsensiSatpamImport done');
    }

    private function writeLog($activity) 
    {
        try {
            DB::beginTransaction();                        
            TpLogAktifitas::insert([
                'username' => 'JOB',
                'aktifitas' => $activity,
                'created_by' => 'Jobs\ParsingDataAbsensi',
                'created_at' => now(),
            ]);            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();            
            Log::error(__CLASS___ . ' >>> Gagal menyimpan log aktifitas');
            Log::error($e->getMessage());
        }
    }
}
