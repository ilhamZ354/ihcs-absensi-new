<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup default database';

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
        $filepath = run_backup_db();
        if(!is_null($filepath))
            $this->info('Backup DB successfully processed. Filepath: ' . $filepath);
        else
            $this->error('Backup DB error, please check application log.');

    }
}
