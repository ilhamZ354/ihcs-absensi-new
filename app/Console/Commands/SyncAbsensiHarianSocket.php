<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\TarikAbsensiHarianSocketJob;

class SyncAbsensiHarianSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:socket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menarik data absensi dari seluruh mesin absensi yang terhubung';

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
        TarikAbsensiHarianSocketJob::dispatch();
        $this->info("Proses sinkronisasi sedang dijalankan dalam sistem, mohon melakukan pengecekan data secara berkala.");
    }
}
