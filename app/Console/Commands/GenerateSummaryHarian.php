<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateAttSummaryJob;
use App\Jobs\SynchronizeZKTimeAttJob;

class GenerateSummaryHarian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan generate summary kehadiran harian berdasarkan data dari Mesin, Mobile dan Upload';

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
        $date = date('Y-m-d');
        SynchronizeZKTimeAttJob::dispatch($date, $date, 'ArtisanCommand');
        GenerateAttSummaryJob::dispatch($date, 'ArtisanCommand');
        $this->info("Proses generate sedang dijalankan dalam sistem, mohon melakukan pengecekan secara berkala.");
    }
}
