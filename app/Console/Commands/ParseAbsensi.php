<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseAbsensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:absensi {id_log_absensi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan parsing data absensi dari file excel yang sudah diupload';

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
        $data = TpLogUploadAbsensi::find($id = $this->argument('id_log_absensi'));

        //check

        $this->info($path);
    }
}
