<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SummaryHarianIndividuJob;
use App\Jobs\SynchronizeZKTimeAttJob;


class GenereateSummaryHarianIndividu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:individu {date} {nik}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan generate summary absensi harian individu. [summary:individu "2019-08-20" "H009208001"]';

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
        $date = date('Y-m-d', strtotime($this->argument('date')));        
        
        SynchronizeZKTimeAttJob::dispatch($date, $date, 'ArtisanCommand');
        SummaryHarianIndividuJob::dispatch($date, $this->argument('nik'));
        $this->info("Proses generate sedang dijalankan dalam sistem, mohon melakukan pengecekan secara berkala.");
    }
}
