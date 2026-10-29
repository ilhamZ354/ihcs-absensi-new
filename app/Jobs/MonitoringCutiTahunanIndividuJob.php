<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use Log;

class MonitoringCutiTahunanIndividuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $nik_simkar;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nik_simkar)
    {
        $this->nik_simkar = $nik_simkar;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::statement("exec sp_new_monitoring_cuti_tahunan_individu ?", [$this->nik_simkar]);
            Log::info("sp_new_monitoring_cuti_tahunan_individu has executed successfully");
        } catch (QueryException $e) {
            Log::error("sp_new_monitoring_cuti_tahunan_individu Error : " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Runtime Error : " . $e->getMessage());            
        } 
    }
}
