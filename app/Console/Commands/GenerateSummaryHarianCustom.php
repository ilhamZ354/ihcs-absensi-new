<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateAttSummaryJob;
use App\Jobs\SynchronizeZKTimeAttJob;


class GenerateSummaryHarianCustom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:daily {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan generate summary kehadiran by tgl. [summary:daily "yyyy-mm-dd"]';

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
        if($this->argument('date') == 'yesterday') {
            $date = date('Y-m-d', strtotime('-1 day'));
        } else {
            $date = date('Y-m-d', strtotime($this->argument('date')));            
        }
        
        SynchronizeZKTimeAttJob::dispatch($date, $date, 'ArtisanCommand');        
        GenerateAttSummaryJob::dispatch($date, 'ArtisanCommand');
        $this->info("Proses generate sedang dijalankan dalam sistem, mohon melakukan pengecekan secara berkala.");
    }
}
