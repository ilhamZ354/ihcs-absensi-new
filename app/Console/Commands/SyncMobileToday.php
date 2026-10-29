<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SynchronizeMobileAttJob;

class SyncMobileToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan sinkronisasi data absensi dari Api mobile telkomsel untuk hari ini saja.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = date('Y-m-d');
        $end = date('Y-m-d');

        SynchronizeMobileAttJob::dispatch(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)), 'SyncMobileCMD');
        $this->info("Proses sinkronisasi sedang dijalankan dalam sistem, mohon melakukan pengecekan data secara berkala.");
    }
}
