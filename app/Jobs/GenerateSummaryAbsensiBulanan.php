<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\QueryException;
use Log;
use DB;

class GenerateSummaryAbsensiBulanan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tahun;
    protected $bulan;
    protected $username;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tahun, $bulan, $username)
    {
        $this->tahun =  $tahun;
        $this->bulan =  $bulan;
        $this->username =  $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::statement("exec sp_admin_generate_all_summary_absensi ?,?", [
                $this->tahun,
                $this->bulan
            ]);
            Log::info("User {$this->username} melakukan generate summary absensi bulanan");
        } catch (QueryException $e) {
            Log::error("User {$this->username} gagal men-generate summary absensi bulanan");
            Log::error($e->getMessage());            
        }


    }
}
