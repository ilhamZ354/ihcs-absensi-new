<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SynchronizeMobileAttJob;

class SyncMobile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:mobile {start_date} {end_date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan sinkronisasi data absensi dari Api mobile telkomsel.';

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
        if($this->argument('start_date') == 'yesterday') {
            $start = date('Y-m-d', strtotime('-1 day'));
        } else {
            $start = date('Y-m-d', strtotime($this->argument('start_date')));            
        }

        if(empty($this->argument('end_date')) || is_null($this->argument('end_date'))) {            
            $end = date('Y-m-d');
        } else {
            $end = date('Y-m-d', strtotime($this->argument('end_date')));
        }
        
        SynchronizeMobileAttJob::dispatch(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)), 'SyncMobileCMD');
        $this->info("Proses sinkronisasi sedang dijalankan dalam sistem, mohon melakukan pengecekan data secara berkala.");
    }
}
