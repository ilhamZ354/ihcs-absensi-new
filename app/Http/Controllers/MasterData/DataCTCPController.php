<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DesktopBaseController;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Excel\Exports\CTCPExport;
use Illuminate\Support\Facades\Storage;
use Log;
use DB;
use Auth;
use Exception;
use App\Models\TrFileUploadCtcp;
use App\Jobs\ParsingFileCTCP;


class DataCTCPController extends DesktopBaseController
{
	protected $route = 'master-data.ctcp.';

	public function __construct()
	{
		parent::__construct();
	}

	public function indexDownload()
	{
		//show the view
        $lockUpload  = env('LOCK_UPLOAD_REPLACE_CTCP');
    	$id = encrypt(1);
		$data = [
    		'route' => $this->route,
    		'breadcrumb' => [
    			'Master-Data' => true,
    			'Download CTCP' => route($this->route.'index-download'),
    		],
    		'id' => $id,
    	];
        if ($lockUpload == false) {
		  return $this->view($this->route . 'index-download',$data);
	   }
       else {
         return redirect('/')->with('swalError', 'Anda tidak bisa mengupload ulang Template karena periode upload telah berakhir.'); 
       }
    }

	public function indexUpload()
	{
		//show the view
        $lockUpload  = env('LOCK_UPLOAD_REPLACE_CTCP');

        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Master-Data' => true,
                'Upload CTCP' => route($this->route.'index-upload'),
            ]
        ];

        if ($lockUpload == true) {
            $formFilled = DB::table('tp_formulir_headers')->select('id')
            ->where('created_by', Auth::user()->getUser()->username)
            ->first();    

            $isVerified = DB::table('tr_file_upload_ctcps')->select('id')
            ->where('uploaded_by', Auth::user()->getUser()->username)
            ->where('verified_status', 1)
            ->first();
            if (count($formFilled) <= 0 && count($isVerified) <= 0) {
                return $this->view($this->route . 'index-upload',$data);
            }
            else if (count($formFilled) > 0) {
               return redirect('/')->with('swalError', 'Anda tidak bisa mengupload ulang Template karena telah mengisi Formulir Absensi.'); 
           }
           else if (count($isVerified) > 0){
               return redirect('/')->with('swalError', 'Anda tidak bisa mengupload ulang Template karena data anda telah diverifikasi.'); 
           }
        }
        else {
            return $this->view($this->route . 'index-upload',$data);
        }

	}

	public function download ($id)
	{
		$id = decrypt($id);
        $user = Auth::user();
        $unit = $user->getUser();
        
        if ($id !== 1) {
            return redirect('/')->with('swalError', 'Kesalahan sistem saat mendownload file Excel'); 
        }

        if($unit->kode_unit =='00') {
        	$filename = "template_ctcp_{$unit->kode_unit}_{$unit->kode_bagian}.xls";
        } else {
        	$filename = "template_ctcp_{$unit->kode_unit}.xls";
        }

        if(is_null($unit->kode_unit)) {
            return redirect()->back()->with('swalError', 'Unit Kerja Anda tidak ditemukan, mohon segera menghubungi Bagian SDM');
        }

        if($unit->kode_unit == '00' && (is_null($unit->kode_bagian) || empty($unit->kode_bagian))) {
            return redirect()->back()->with('swalError', 'Bagian Kerja Anda tidak ditemukan, mohon segera menghubungi Bagian SDM');
        }

        try {
        	 $template = collect(DB::select("EXEC sp_get_template_ctcp ?,?", [$unit->kode_unit, $unit->kode_bagian]));

            Log::info($user->getUser()->username . " mendownload template {$filename}");
            // return Excel::download(new DP2KExport($template), $filename);
            return (new CTCPExport($template))->download($filename, \Maatwebsite\Excel\Excel::XLS);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('swalError', 'File gagal didownload, mohon segera menghubungi Bagian SDM');
        }
	}
    
	public function store(Request $request) {
        $request->validate([
            'file_ctcp' => 'required|file',
        ]);
        $user = Auth::user()->getUser();
        $unit = $user->kode_unit;
        $bagian = '';
        if(!is_null($user->kode_bagian)) {
            $bagian = '_'.$user->kode_bagian;           
        }
        try {
            $path = $request->file('file_ctcp')->storeAs(
                'file_ctcp', date('Y').'_'.$unit.$bagian.'_'.$user->username.'_'.time().'.'.$request->file_ctcp->getClientOriginalExtension()
            );
            Log::info("Filepath : " . $path);

            if(!is_null($path)) {
                Log::info('User '.$user->username.' berhasil mengupload data template CTCP.'); 
                return $this->_db_store(date('Y'), $request->file_ctcp->getClientOriginalName(), $path, $user->username, $unit, $user->kode_bagian);
                // return redirect()->route($this->route . 'index')->with('swalSuccess', 'File penilaian berhasil diupload ke server');             
            }else {
                return redirect()->route($this->route . 'index-upload')->with('swalError', 'Gagal menyimpan file CTCP. Mohon menghubungi Bagian SDM');                
            }
        } catch (Exception $e) {
            Log::error('Gagal mengupload data CTCP '. $e->getMessage());
            return redirect()->route($this->route . 'index-upload')->with('swalError', 'Terjadi kesalahan di sistem saat memproses permintaan. Mohon menghubungi Bagian SDM.');
        }
    }

    private function _db_store($tahun, $original_filename, $path, $username, $unit, $bagian = null) {
        try {
            
            DB::beginTransaction();
            $data = [
                'tahun' => $tahun,
                'original_filename' => $original_filename,
                'path' => $path,
                'kode_unit' => $unit,
                'uploaded_by' => $username,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            
            if(!is_null($bagian)) {
                $data['kode_bagian'] = $bagian;
            }

            $file_upload_id = TrFileUploadCtcp::insertGetId($data);
            DB::commit();
            Log::info($username . ' berhasil mengupload file CTCP, path: '.$path);

            //dispatch this job
            ParsingFileCTCP::dispatch($file_upload_id, Auth::user()->getUser()->username);
            return redirect()->route($this->route . 'index-upload')->with('swalWarning', 'File Template CTCP berhasil diupload ke server. Sistem akan melakukan verifikasi pada file anda terlebih dahulu. Silahkan refresh halaman ini untuk hasilnya.');  
        } catch (QueryException $e) {
            DB::rollBack();
            $deletedRows = TrFileUploadCtcp::where('id', $file_upload_id)->delete();
            $this->_removeUploadedFile($path);
            Log::error('Gagal menyimpan data upload ke Database '. $e->getMessage());
            return redirect()->route($this->route . 'index-upload')->with('swalError', 'Terjadi kesalahan di sistem saat mengupload file. Mohon menghubungi Bagian SDM.');
        }
    }

    private function _removeUploadedFile($path) {
        Storage::delete($path);
    }

}
