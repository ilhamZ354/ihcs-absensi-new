<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Absensi\Crawler\AbsensiSQLite;

class SynchronizeZKTimeAttJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $startDate;
    private $endDate;
    private $username;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($startDate, $endDate, $username)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AbsensiSQLite::getAbsensiMesinByDate($this->startDate, $this->endDate);
        \Log::info("Sinkronisasi ZKTime berhasil dilakukan oleh {$this->username}");
    }
}
