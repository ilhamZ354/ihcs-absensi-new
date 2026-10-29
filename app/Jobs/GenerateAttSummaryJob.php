<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use DB;

class GenerateAttSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $date;
    protected $createdBy;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date, $createdBy)
    {
        $this->date = $date;
        $this->createdBy = $createdBy;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            // Log::info("Starting Mesin ". $this->date);
            Log::info("Generate GenerateAttSummaryJob is hang! so we move it to SQL Server Agent");
            // DB::select("exec dbo.sp_new_generate_detail_absensi_harian ?", [$this->date]);        

            // Log::info("Starting Non Mesin ". $this->date);
            // DB::select("exec dbo.sp_new_generate_detail_absensi_harian_nonmesin ?", [$this->date]);
            // // DB::statement("exec dbo.sp_new_generate_detail_absensi_harian_satpam ?", [$this->date]);
            // Log::info("GenerateAttSummaryJob (for date:". $this->date .") has executed successfully");
            
        } catch (QueryException $e) {
            Log::error("GenerateFullDaysAbsensiJob Error : " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Runtime Error : " . $e->getMessage());            
        }
    }
}
