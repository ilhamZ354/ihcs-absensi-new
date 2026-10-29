<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use Log;
use DB;

class AbsensiHarianController extends Controller
{
    function __construct ()
    {
    	$this->middleware('absensi.api');
    }

    public function daily(Request $request)
    {
        /*
        { eventDate: '1', eventName: 'Kerja', color:'K'},
        { eventDate: '2', eventName: 'Cuti Bersama', color:'CB'},
        { eventDate: '3', eventName: 'Kerja', color:'K'},
        { eventDate: '4', eventName: 'Kerja', color:'K'},
        { eventDate: '5', eventName: 'Kerja', color:'K'},
        { eventDate: '6', eventName: 'Kerja', color:'K'},
        { eventDate: '7', eventName: 'Libur', color:'L'},
        { eventDate: '8', eventName: 'Libur', color:'L'},
        { eventDate: '9', eventName: 'Izin Tidak Bekerja dan Tidak Mendapat Gaji', color:'P1'},
        */

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $nik = $request->nik;

         Log::info($tahun.' '.$bulan.' '.$nik);

        try {
            $rows = \DB::connection('dev_absensi')->select("exec sp_get_summary_absensi_harian ?,?,?", [
                $tahun, $bulan, $nik
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Success', 
                'data' => $this->_event_adjusting($rows, $tahun, $bulan),
            ]);
        } catch (QueryException $e) {
            Log::error('Gagal mengambil data dari database, hubungi admin.');
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data dari database, hubungi admin.',
            ]);         
        }
    }

    private function _event_adjusting($rows, $tahun, $bulan) {

       if(sizeof($rows) == 0)  {
           exit();
        }

        $newRows = [];
        $dates = [];

        foreach ($rows[0] as $key => $value) {
            $_dates = [];
            if(in_array($key, ['pin', 'nik_simkar', 'tahun', 'bulan'])) {
               $newRows[$key] = $value;
            } else {
                $_dates = [
                    'eventDate' => $key,
                    'color' => $value,
                    'eventName' => nama_kode_absensi($value, $tahun, $bulan, $key),
                ];
                array_push($dates, $_dates);
            }
        }
        $newRows['dates'] = $dates;
        return $newRows;
    }

    public function detail(Request $request)
    {
        $pin = $request->input('pin');
        $tgl = $request->input('tgl');

        $details = DB::connection('dev_absensi')->select('select * from dbo.fn_get_detail_finger_harian(?,?)', [$pin, $tgl]);
        if(!is_null($details) && !empty($details)) {
            return response()->json([
                'status' => true,
                'message' => 'Success', 
                'data' => collect($details)->first()
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data kosong', 
            ]);
        }
    }
    
}
