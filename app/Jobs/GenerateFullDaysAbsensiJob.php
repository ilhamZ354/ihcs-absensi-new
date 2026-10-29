<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use DB;

class GenerateFullDaysAbsensiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tahun;
    protected $bulan;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tahun, $bulan)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::statement("exec sp_generate_fulldays_absensi_details ?, ?", [$this->tahun, $this->bulan]);
            Log::info("GenerateFullDaysAbsensiJob has executed successfully");
        } catch (QueryException $e) {
            Log::error("GenerateFullDaysAbsensiJob Error : " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Runtime Error : " . $e->getMessage());            
        } 
    }
}
