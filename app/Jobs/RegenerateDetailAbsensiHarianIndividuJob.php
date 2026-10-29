<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\QueryException;
use DB;
use Log;

class RegenerateDetailAbsensiHarianIndividuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $nik_simkar;
    private $tgl_mulai;
    private $tgl_selesai;
    private $created_by;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nik_simkar, $tgl_mulai, $tgl_selesai, $created_by)
    {
        $this->nik_simkar = $nik_simkar;
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
        $this->created_by = $created_by;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::statement("exec sp_new_regenerate_detail_absensi_harian_individu ?,?,?,?", [$this->nik_simkar, $this->tgl_mulai, $this->tgl_selesai, $this->created_by]);
            Log::info($this->created_by . " menjalankan regenerate detail absensi untuk nik:" . $this->nik_simkar);
        } catch (QueryException $e) {
            Log::error("DATABASE ERROR SAAT menjalankan regenerate detail absensi untuk nik:" . $this->nik_simkar. " >>> " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("RUNTIME ERROR SAAT menjalankan regenerate detail absensi untuk nik:" . $this->nik_simkar. " >>> " . $e->getMessage());
        }
    }
}
