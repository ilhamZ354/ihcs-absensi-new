<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use Log;

class SummaryHarianIndividuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $nik;
    private $tgl;
    private $created_by = 'SummaryHarianIndividuJob';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tgl, $nik)
    {
        $this->tgl = $tgl;
        $this->nik = $nik;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info([$this->tgl, $this->nik, $this->created_by]);
        try {
            DB::statement("exec sp_new_generate_detail_absensi_harian_individu ?,?,?", [$this->tgl, $this->nik, $this->created_by]);
            Log::info($this->created_by . " menjalankan generate detail absensi harian untuk nik:" . $this->nik);
        } catch (QueryException $e) {
            Log::error("DATABASE ERROR SAAT menjalankan generate detail absensi harian untuk nik:" . $this->nik. " >>> " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("RUNTIME ERROR SAAT menjalankan generate detail absensi harian untuk nik:" . $this->nik. " >>> " . $e->getMessage());
        }
    }
}
