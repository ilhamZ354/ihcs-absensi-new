<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\IHCS\Controllers\TelegramController as Telegram;

class TelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message;
    private $client;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $client = 'ptpn4')
    {
        $this->message = $message;
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $telegram = new Telegram($this->client);
        $telegram->sendReport($this->message, "HTML");
    }
}
