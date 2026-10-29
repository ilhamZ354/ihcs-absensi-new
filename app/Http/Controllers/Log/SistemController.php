<?php

namespace App\Http\Controllers\Log;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use Illuminate\Support\Facades\Storage;

class SistemController extends DesktopBaseController
{
    protected $route = 'log.sistem.';

    public function __construct()
    {
    	parent::__construct();
    }

    public function index()
	{
    	$logs = $this->_get_log_files();
		$data = [
			'route' => $this->route,
			'breadcrumb' => [
				'Log' => true,
				'Sistem' => route($this->route.'index'),
			],
			'log_files' => $logs,
		];

		return $this->view($this->route . 'index', $data);
	}

	public function getTable()
    {
        return \DataTables::of($this->_get_log_files())
            ->addColumn('file_log', function ($log) {            	
                $_log = explode(DIRECTORY_SEPARATOR, $log);
                return ucfirst($_log[count($_log) - 1]);
            })
            ->addColumn('action', function ($log) {
            	$_log = explode(DIRECTORY_SEPARATOR, $log);
                $temp = '';
                $temp .= '<a href="javascript:;" onclick="showLog(event, \''.route($this->route . 'show', [$_log[count($_log) - 1]]).'\')" class="btn btn-success btn-sm" title="Show Log"><i class="fa fa-eye"></i></a> ';
                $temp .= '<a href="javascript:;" onclick="clearLog(event, \''.route($this->route . 'clear', [encrypt($_log[count($_log) - 1])]).'\')" class="btn btn-danger btn-sm" title="Clear Log"><i class="fa fa-trash"></i></a> ';
                return $temp;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($file)
    {
    	$data = [];
    	try {
	    	$content = file_get_contents(storage_path('logs\\' . $file));
	    	$data['content'] = $content;
	    } catch (\Exception $e) {
	    	$data['content'] = null;
	    }	    
		return $this->view($this->route . 'show', $data);
    }

	public function clear($file)
	{
		$file = decrypt($file);
		$path = storage_path('logs\\' . $file);

		return response()->json(['status' => exec('echo "" > ' . $path) == ""]);
	}

	private function _get_log_files()
	{
		$path = storage_path('logs');
		$dir = scandir($path);
		$logs = [];
		foreach ($dir as $filename) {
			if(substr($filename, -3) == 'log') {
				array_push($logs, $path . DIRECTORY_SEPARATOR . $filename);
			}
		}

		return $logs;
	}
}
