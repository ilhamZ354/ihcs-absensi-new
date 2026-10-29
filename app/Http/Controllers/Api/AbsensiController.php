<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\DesktopBaseController;
use App\Jobs\GenerateAttSummaryJob;
use App\Jobs\GenerateSummaryAbsensiHarianJob;
use App\Models\TrConfigApp;
use App\Models\TrNotification;
use App\Jobs\SynchronizeMobileAttJob;
use Auth;
use DB;
use Log;

class AbsensiController extends DesktopBaseController
{
	public function __construct()
    {
    	parent::__construct();
    }
    
    /**
    * Run DB Procedure to generate full days absensi
    * @param Request $request
    * @return mixed
    */
    public function full_day_absensi(Request $request)
    {
    	$this->_tahunBulanValidate($request);

    	$params = [
    		'procedure_name' => 'sp_generate_fulldays_absensi_details',
    		'tahun' => $request->tahun,
    		'bulan' => toNumberOfMonth($request->bulan),
    	];

    	return $this->_exec_procedure_with_tahun_bulan($params);
    }

    /**
    * Run DB Procedure to generate telat/cepat pulang
    * @param Request $request
    * @return mixed
    */
    public function telat_cepat_pulang(Request $request)
    {
    	$this->_tahunBulanValidate($request);

    	$params = [
    		'procedure_name' => 'sp_generate_telat_datang_cepat_pulang',
    		'tahun' => $request->tahun,
    		'bulan' => toNumberOfMonth($request->bulan),
    	];

    	return $this->_exec_procedure_with_tahun_bulan($params);
    }

    /**
    * Run DB Procedure to generate hari minggu
    * @param Request $request
    * @return mixed
    */
    public function hari_minggu(Request $request)
    {
        $request->validate([
            'tahun' =>'required'
        ]);

        try {
            DB::statement('exec sp_generate_hari_minggu ?', [$request->tahun]);
            Log::info(__METHOD__ . " >>> " . Auth::user()->getUser()->username ." berhasil men-genereate hari minggu di tahun {$request->tahun}");
            return $this->_successResponse();
        } catch (QueryException $e) {
            Log::error("Gagal exec sp_generate_hari_minggu");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        } catch (\Exception $e) {
            Log::error("RUNTIME ERROR");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        }
    }

    /**
    * Run DB Procedure to generate cuti panjang
    * @param Request $request
    * @return mixed
    */
    public function cuti_panjang(Request $request)
    {
        /*$this->_tahunBulanValidate($request);

        $params = [
            'procedure_name' => 'sp_generate_kuota_cuti_panjang',
            'tahun' => $request->tahun,
            'bulan' => toNumberOfMonth($request->bulan),
        ];

        return $this->_exec_procedure_with_tahun_bulan($params);*/
        try {
            DB::statement('exec sp_generate_kuota_cuti_panjang');
            Log::info(__METHOD__ . " >>> " . Auth::user()->getUser()->username ." berhasil men-genereate kuota cuti panjang");
            return $this->_successResponse();   
        } catch (QueryException $e) {
            Log::error("Gagal exec sp_generate_kuota_cuti_panjang");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        } catch (\Exception $e) {
            Log::error("RUNTIME ERROR");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        }
    }

    /**
    * Run DB Procedure to generate cuti tahunan
    * @param Request $request
    * @return mixed
    */
    public function cuti_tahunan(Request $request)
    {
        try {
            DB::statement('exec sp_generate_kuota_cuti_tahunan ?', [$request->tahun]);
            Log::info(__METHOD__ . " >>> " . Auth::user()->getUser()->username ." berhasil men-genereate kuota cuti tahunan");
            return $this->_successResponse();   
        } catch (QueryException $e) {
            Log::error("Gagal exec sp_generate_kuota_cuti_tahunan");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        } catch (\Exception $e) {
            Log::error("RUNTIME ERROR");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        }
    }

    /**
    * Run DB Backup
    * @return mixed
    */
    public function backup_db()
    {
        $filepath = run_backup_db();
        
        if(!is_null($filepath)) {

            send_file_to_safety_place($filepath);

            $this->writeLog('SUCCESS: Backup database berhasil. Filepath: '. $filepath);
            Log::info(__METHOD__ . " >>> " . Auth::user()->getUser()->username . ' Berhasil melakukan backup database. Filepath: '. $filepath);
            return $this->_successResponse();                
        } else {
            Log::error("Backup database gagal.");
            return $this->_errorResponse();
        }
    }

    /**
    * Reusable function to run db procedures
    * which require tahun and bulan only
    * @param array $params
    * @return mixed
    */
    private function _exec_procedure_with_tahun_bulan($params = [])
    {
        Log::info("params[] = " . json_encode($params));
        try {
            DB::statement("exec ".$params['procedure_name']." ?,?", [
                $params['tahun'],
                $params['bulan'],
            ]);     
            $this->writeLog('Berhasil menjalankan prosedur ' . $params['procedure_name']);
            Log::info(__METHOD__ . " >>> " . Auth::user()->getUser()->username . ' Berhasil menjalankan prosedur. '. $params['procedure_name']);
            return $this->_successResponse();
        } catch (QueryException $e) {
            Log::error("Gagal exec " . $params['procedure_name']);
    		Log::error($e->getMessage());
    		return $this->_errorResponse();
    	} catch (\Exception $e) {
    		Log::error("RUNTIME ERROR");
    		Log::error($e->getMessage());
    		return $this->_errorResponse();
    	}
    }

    /**
        Mengambil nilai Cuti Sesuai jenis_cuti
        Berdasarkan NIK.
        Compare tgl_mulai dan tgl_selesai pada tabel tr_cuti_tahunan
        dengan tanggal yang ada pada form pengajuan cuti (Request)
    **/
    public function getCuti(Request $request)
    {

        $niks =  explode('/', $request->input('nik'));
        $nik_simkar = $niks[0];

        $dbTglMulai = date('Y-m-d', strtotime($request->input('tgl_mulai')));
        $dbTglSelesai = date('Y-m-d', strtotime($request->input('tgl_selesai')));
        $existOnDB =  DB::table('tp_formulir_headers')
        ->where('nik_simkar',$nik_simkar)
        ->whereRaw("'". $dbTglMulai . "' <= tgl_mulai and '". $dbTglSelesai . "' >= tgl_selesai")
        ->where('status',1)
        ->first();
        // return $this->toJson($existOnDB);

        if($request->input('jenis') == 'CT') {
            $dbCuti = $this->getCompleteCTCP('CT', $nik_simkar, $dbTglMulai, $dbTglSelesai);
            $jenis_cuti = 'Cuti Tahunan';
        }else if($request->input('jenis') == 'CP') {
            $dbCuti = $this->getCompleteCTCP('CP', $nik_simkar, $dbTglMulai, $dbTglSelesai);
            $jenis_cuti = 'Cuti Panjang';
        }
        else {
            $dbCuti = null;
            $jenis_cuti = DB::table('tr_jenis_absens')
            ->select('nama')->where('kode', $request->input('jenis'))
            ->first()->nama;
        }

        return $this->toJson([$dbCuti,$jenis_cuti, $existOnDB]);
    }

    public function getCompleteCTCP($jenis, $nik_simkar, $tglMulai, $tglSelesai)
    {
        if($jenis == 'CT') {
            $dbCuti = DB::table('v_cuti_tahunan_with_cb')
            ->select('sisa','tgl_mulai','tgl_berakhir');
        }else if($jenis == 'CP') {
            $dbCuti = DB::table('tr_kuota_cuti_panjangs')
            ->select(DB::raw("(kuota - terpakai) as sisa"),'tgl_mulai','tgl_berakhir');
        }
      
        $dbCuti = $dbCuti->where('nik_simkar',$nik_simkar)
        ->whereRaw("'". $tglMulai . "' between tgl_mulai and tgl_berakhir")
        ->whereRaw("'". $tglSelesai . "' between tgl_mulai and tgl_berakhir")
        ->where('status',1)
        ->first();

        return $dbCuti;
    }

    public function configPathMdb(Request $request)
    {
        $request->validate(['path' => 'required']);
        
        try {
            DB::beginTransaction();
            $newConfig = TrConfigApp::insertGetId([
                'key' => 'path_mdb',
                'value' => $request->path,
                'status' => 1,
                'created_by' => Auth::user()->getUser()->username,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            TrConfigApp::where('key', 'path_mdb')
            ->whereRaw("id < {$newConfig}")
            ->update(['status' => 0]);            
            DB::commit();
            return $this->_successResponse();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error("Gagal menyimpan path_mdb baru ");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        } catch (\Exception $e) {
            Log::error("RUNTIME ERROR");
            Log::error($e->getMessage());
            return $this->_errorResponse();
        }
    }

    public function getPathMdb()
    {
        return response()->json(get_current_mdb_path());
    }

    /**
    * Validate tahun and bulan
    * @param Request $request
    * @return void
    */
    private function _tahunBulanValidate(Request $request) : void
    {
    	$request->validate([
    		'tahun' => 'required',
    		'bulan' => 'required'
    	]);
    }

    /**
    * Default error response
    * @return json
    */
    private function _errorResponse()
    {
    	return $this->toJson([
    		'success' => false,
    		'message' => 'Gagal mengeksekusi tugas, mohon memeriksa Log Aplikasi.',
    	]);
    }

    /**
    * Default success response
    * @return json
    */
    private function _successResponse()
    {
    	return $this->toJson([
    		'success' => true,
    		'message' => 'Tugas berhasil dieksekusi.',
    	]);
    }

    /*
    fungsi untuk memeriksa apakah tanggal mulai dan selesai pengajuan absensi
    ada pada batas tglmulai dan tanggal selesai yang valid sesuai dengan data peserta
    */
    public function checkDateCTCP(Request $request)
    {
        // dd($request->all());
        return response()->json($request->all());
    }

    /* fungsi substring untuk mengambil hanya nilai NIK_SIMKAR*/
    private function getNIKSimkar($nik)
    {
        $niks =  explode('/', $nik);
        $nik_simkar = $niks[0];
        // $nik_sap    = $niks[1];
        return $nik_simkar;
    }

    public function setAllNotifsReaded(Request $request)
    {
        if(\Hash::check('niasharapan@gmail.com', $request->token)) {
            $usernameTarget = Auth::user()->getUser()->username;
            try {
                TrNotification::where('username_target', $usernameTarget)
                    ->update(['readed' => true, 'updated_at' => date('Y-m-d H:i:s')]);
                Log::info($usernameTarget . ' telah membaca seluruh notifikasinya.');
                return json_encode(true);
            } catch (QueryException $e) {
                Log::error("Gagal mengupdate notifikasi. username_target:".$usernameTarget);
                Log::error($e->getMessage());
                return json_encode(false);
            }

        }
    }

    public function generateSummaryAbsensi(Request $request)
    {
        $request->validate([
            'tgl' => 'required',
        ]);
        // $this->_tahunBulanValidate($request);

        /*$params = [
            'procedure_name' => 'sp_admin_generate_all_summary_absensi',
            'tahun' => $request->tahun,
            'bulan' => toNumberOfMonth($request->bulan),
        ];

        return $this->_exec_procedure_with_tahun_bulan($params);*/

        // GenerateSummaryAbsensiBulanan::dispatch($request->tahun, toNumberOfMonth($request->bulan), Auth::user()->getUser()->username);
        GenerateAttSummaryJob::dispatch(date('Y-m-d', strtotime($request->tgl)), Auth::user()->getUser()->username);
        return $this->toJson([
            'success' => true,
            'message' => 'Tugas sedang dieksekusi dalam sistem, silahkan mengecek secara berkala.',
        ]);
    }

    public function generateSummaryHarian(Request $request)
    {
        GenerateSummaryAbsensiHarianJob::dispatch();
        return $this->toJson([
            'success' => true,
            'message' => 'Mohon menunggu, tugas sedang dieksekusi dalam sistem.',
        ]);
    }

    public function syncAttMobile(Request $request)
    {
        $request->validate([
            'mulai' => 'required',
            'selesai' => 'required',
        ]);

        $params = [
            'mulai' => date('Y-m-d', strtotime($request->mulai)),
            'selesai' => date('Y-m-d', strtotime($request->selesai)),
        ];

        SynchronizeMobileAttJob::dispatch($params['mulai'], $params['selesai'], Auth::user()->getUser()->username);

        return $this->toJson([
            'success' => true,
            'message' => 'Mohon menunggu, tugas sedang dieksekusi dalam sistem.',
        ]);
    }

}
