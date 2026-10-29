<?php

namespace App\Jobs;

use Illuminate\Database\QueryException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\AttMobileService;
use App\Jobs\TelegramJob;
use Log;
use DB;

class SynchronizeMobileAttJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $startDate;
    private $endDate;
    private $username;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($startDate, $endDate, $username)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // input dulu ke tp_log_mobile_syncs
        DB::beginTransaction();
        try {
            $idLog = DB::table('tp_log_mobile_syncs')->insertGetId([
                'mulai' => $this->startDate,
                'selesai' => $this->endDate,
                'username' => $this->username,
                'created_at' => now()
            ]);

            $mobile = new AttMobileService();
            $rows =  $mobile->getByDateRange($this->startDate, $this->endDate);

            foreach ($rows as $row) {

                DB::table('tmp_mobile_absensis')->insert([
                    'id_sync_log' => $idLog,
                    'nik' => $row->nik,
                    'msisdn' => $row->msisdn,
                    'nama' => $row->nama,
                    'tanggal' => date('Y-m-d', strtotime($row->tanggal)),
                    // 'jam_clockin' => date('H:i:s', strtotime($row->tanggal . ' ' .$row->jam_clockin)),
                    'jam_clockin' => is_null($row->jam_clockin) || empty($row->jam_clockin) ? null : date('H:i:s', strtotime($row->tanggal . ' ' .$row->jam_clockin)),
                    'jam_clockout' => is_null($row->jam_clockout) ? null : date('H:i:s', strtotime($row->tanggal . ' ' .$row->jam_clockout)),
                    'reason_in' => $row->reason_in,
                    'created_by' => $this->username
                ]);
            }

            DB::commit();
            $message = "Synchronize Mobile Attendance Job berhasil melakukan sinkronisasi data pada " . date('Y-m-d H:i:s');
            TelegramJob::dispatch("<b>".env('APP_NAME') . " " . $message, 'harapan');
            Log::info($message);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error("QueryException SynchronizeMobileAttJob gagal. >>>> " . $e->getMessage());
            TelegramJob::dispatch("<b>".env('APP_NAME') . " " . "QueryException SynchronizeMobileAttJob gagal. >>>> " . $e->getMessage(), 'harapan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception SynchronizeMobileAttJob gagal. >>>> " . $e->getMessage());
            TelegramJob::dispatch("<b>".env('APP_NAME') . " " . "Exception SynchronizeMobileAttJob gagal. >>>> " . $e->getMessage(), 'harapan');
        }
        
        DB::statement("exec sp_new_parsing_absensi_mobile_harian :tgl_mulai, :tgl_selesai, :created_by, :idLog", [
            'tgl_mulai' => $this->startDate, 
            'tgl_selesai' => $this->endDate,
            'created_by' => $this->username,
            'idLog' => $idLog                
        ]);
    }
}
