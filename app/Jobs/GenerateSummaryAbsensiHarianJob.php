<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class GenerateSummaryAbsensiHarianJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $batPath = resource_path('assets/library/absensi-harian/run.bat');
            $response = exec($batPath);
            Log::info(__CLASS__ . " >>> run command " . $batPath);
        } catch (QueryException $e) {
            Log::error(__CLASS__ . " >>> Job gagal menjalankan generate summary absensi harian");
            Log::error($e->getMessage());            
        }
    }
}
