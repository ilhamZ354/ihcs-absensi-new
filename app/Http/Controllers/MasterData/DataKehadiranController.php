<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use App\Models\TpLogUploadAbsensi;
use App\Models\TrAbsensiDetail;
use App\Services\Excel\AbsensiImport;
use App\Jobs\ParsingDataAbsensi;
use DataTables;
use Storage;
use DB;
use Auth;
use Excel;

class DataKehadiranController extends DesktopBaseController
{
    protected $route = 'master-data.data-kehadiran.';

    public function __construct()
    {
    	parent::__construct();
    }

    public function index()
    {
    	$data = [
    		'route' => $this->route,
    		'breadcrumb' => [
    			'Master Data' => true,
    			'Data Kehadiran' => route($this->route.'index'),
    		],
    	];
    	return $this->view($this->route . 'index', $data);
    }

    public function getTable()
    {
        $countOfTbl = DB::table('sysobjects')->where('type', 'U')->where('name', 'tmp_absensi_details')->count();        
        if($countOfTbl > 0 && DB::table('tmp_absensi_details')->count() > 0) {
        $atts = DB::table('tmp_absensi_details as tbl')->select('tbl.id', 'tbl.pin', 'p.nik_simkar', 'p.nama_mesin'
            , 'tbl.tgl'
            , DB::raw('year(tbl.tgl) as tahun')
            , DB::raw('month(tbl.tgl) as bulan')
            , DB::raw('day(tbl.tgl) as hari')
            , 'tbl.checkin_time', 'tbl.checkout_time', 'tbl.telat_datang', 'tbl.cepat_pulang')
            ->join('tp_pesertas as p', 'p.pin', 'tbl.pin');
        } else {
            $atts = DB::table('tr_absensi_details as tbl')->select('tbl.id', 'tbl.pin', 'p.nik_simkar', 'p.nama_mesin'
            , 'tbl.tgl'
            , DB::raw('year(tbl.tgl) as tahun')
            , DB::raw('month(tbl.tgl) as bulan')
            , DB::raw('day(tbl.tgl) as hari')
            , 'tbl.checkin_time', 'tbl.checkout_time', 'tbl.telat_datang', 'tbl.cepat_pulang')
            ->join('tp_pesertas as p', 'p.pin', 'tbl.pin'); //rawQuery
        }

        return DataTables::of($atts)
            // ->addColumn('action', function ($att) {
            //     $temp = ' <a href="'.route($this->route . 'show', encrypt($att->id)).'" class="btn btn-primary btn-sm" title="Detail Data"><i class="fa fa-search"></i></a> ';
            //     return $temp;
            // })
            ->filterColumn('tgl', function($query, $keyword) {
                dd($keyword);
                $keyword = date('Y-m-d', strtotime($keyword));
                $query->whereRaw("tr_absensi_details.tgl like ? %{$keyword}%");
            })
            // ->rawColumns(['action'])
            ->make(true);
    }

    public function upload()
    {
    	$data = [
    		'route' => $this->route,
    		'breadcrumb' => [
    			'Master Data' => true,
    			'Data Kehadiran' => route($this->route.'index'),
    			'Upload Data Kehadiran' => route($this->route.'upload'),
    		],
    		'units' => UnitBagianMaster::where('status', 1)->orderBy('kodeunitbagian', 'asc')->get(),
    		'bagians' => TmBagian::where('isactive', 'Y')->orderBy('kelompok', 'asc')->get(),
    	];
    	return $this->view($this->route . 'upload', $data);	
    }

    public function saveUpload(Request $request)
    {
    	$request->validate([
    		'tahun' => 'required|max:4',
    		'bulan' => 'required',
    		'jenis_data' => 'required',
    		'unit' => 'required_with:bagian',
    		'bagian' => 'nullable',
    		'nik_peserta' => 'nullable',
    		'nama_peserta' => 'nullable',
    		'file_absensi' => 'required|file',
    	]);
    	
        //try to save file first
        try {
            $path = $request->file('file_absensi')->storeAs(
                'data-kehadiran', 'ABSENSI_'.$request->tahun.toNumberOfMonth($request->bulan).'_'.$request->jenis_data.'_'.time().'.'.$request->file_absensi->getClientOriginalExtension()
            );

            if(!is_null($path)) {
                $this->writeLog('Berhasil mengupload data absensi'); 
                return $this->storeUploadDetails($request, $path);
            }
            return redirect()->route($this->route . 'upload')->withError('Gagal menyimpan file absensi. Silahkan hubungi administrator');
        } catch (Exception $e) {
            $this->writeLog('Gagal mengupload data absensi');
            Log::error('Gagal menyimpan file absensi '. $e->getMessage());
            return redirect()->route($this->route . 'upload')->withError('Gagal menyimpan file absensi. Silahkan hubungi administrator');
        }
    }

    public function uploadedFiles()
    {
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Master Data' => true,
                'Data Kehadiran' => route($this->route.'index'),
                'Upload Data Kehadiran' => route($this->route.'upload'),
                'Files' => route($this->route.'uploaded-files'),
            ],
        ];
        return $this->view($this->route . 'uploaded-files', $data);
    }

    public function tableUploadedFiles()
    {
        $files = TpLogUploadAbsensi::select('tp_log_upload_absensis.id', 'tp_log_upload_absensis.tahun'
            , 'tp_log_upload_absensis.bulan'
            , 'tp_log_upload_absensis.jenis_data'
            , DB::raw('isnull(b.namabagian, isnull(u.namaunitbagian, nama_peserta)) as uraian')
            , 'b.namabagian'
            , 'tp_log_upload_absensis.nama_peserta'
            , 'tp_log_upload_absensis.file_absensi')
            ->leftjoin('unitbagianmaster as u', 'u.kodeunitbagian', 'tp_log_upload_absensis.kode_unit')
            ->leftjoin('tm_bagian as b', 'b.kodebagian', 'tp_log_upload_absensis.kode_bagian');            

        return DataTables::of($files)
            ->filterColumn('uraian', function($query, $keyword) {
                $query->whereRaw('isnull(u.namaunitbagian, isnull(b.namabagian, nama_peserta)) like ?', ["%{$keyword}%"]);
            })
            ->addColumn('file_absensi', function ($att) {
                return '<a href="javascript:;" class="btn btn-square btn-outline-primary btn-sm download_absensi_file" title="Download file" 
                    onclick="event.preventDefault(); document.getElementById(\'form-download-'. $att->id .'\').submit();"><i class="fa fa-download"></i></a> '
                . '<form id="form-download-'. $att->id .'" action="' . route($this->route . 'download', encrypt($att->id)) .'" method="POST" style="display: none;">' . csrf_field() .'</form>';
            })
            ->rawColumns(['file_absensi'])
            ->make(true);
    }

    private function storeUploadDetails(Request $request, $path)
    {
        try {
            DB::beginTransaction();
            $idLogUpload = TpLogUploadAbsensi::insertGetId([
                'tahun' => $request->input('tahun'),
                'bulan' => toNumberOfMonth($request->input('bulan')),
                'jenis_data' => $request->input('jenis_data'),
                'kode_unit' => $request->input('unit'),
                'kode_bagian' => $request->input('bagian'),
                'nik_peserta' => $request->input('nik_peserta'),
                'nama_peserta' => $request->input('nama_peserta'),
                'file_absensi' => $path,
                'created_by' => Auth::user()->getUser()->username,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            DB::commit();   
            $this->writeLog('Berhasil menyimpan data absensi ke database');

            $this->parsingDataAbsensi($idLogUpload);

            return redirect()->route($this->route . 'index')->with('swalSuccess', 'Berhasil menyimpan file dan data absensi');
        } catch (QueryException $e) {
            DB::rollBack();            
            Storage::delete($path);
            $this->writeLog('Gagal menyimpan data absensi ke database');      
            return redirect()->route($this->route . 'upload')->with('swalError', 'Terjadi kesalahan di server saat menyimpan file dan data. Silahkan hubungi administrator');
        } catch (\Exception $e) {
            \Log::error($e);
            return redirect()->route($this->route . 'upload')->with('swalError', 'Terjadi kesalahan di server saat menyimpan file dan data. Silahkan hubungi administrator');
        }
    }

    /**
    * Menjalankan job untuk memparsing data absensi
    * @param int $id
    * @return void
    */
    public function parsingDataAbsensi($id)
    {
        ParsingDataAbsensi::dispatch($id);
        \Log::info("ParsingDataAbsensi dijalankan dengan id: {$id}");
    }

    public function checkDuplicate(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $jenis_data = $request->input('jenis_data');
        $unit = $request->input('unit');
        $bagian = $request->input('bagian');
        $nik = $request->input('nik');

        return true;
    }

    public function download($id)
    {
        $id = decrypt($id);
        $logUpload = TpLogUploadAbsensi::find($id);
        $file = $logUpload->file_absensi;

        if(file_exists(storage_path('/app/'.$file))) {
            return response()->download(storage_path('/app/'.$file));
        }
        return redirect()->route($this->route . 'uploaded-files')->with('error', 'File tidak ditemukan');
    }
    
}
