<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TpLogAktifitas;
use Auth;
use DB;

class DesktopBaseController extends Controller
{
    private $view = 'desktop.pages.';

    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function view($view, $data = []) {
        $br = [];
        if(isset($data['breadcrumb'])) {
            foreach($data['breadcrumb'] as $name => $url) {
                if($url === '#') {
                    $br[$name] = 'javascript:;';
                } else {
                    $br[$name] = $url;
                }
            }
    		// foreach($data['breadcrumb'] as $link => $value) {
      //           if($link == '#') {
      //               $breadcrumb[route('home')] = $value;
      //           } else if(substr($link, 0, 2) == '__') {
      //               $breadcrumb[str_replace('__', '', $link)] = $value;
      //           } else {
	    	// 		$breadcrumb[route($this->route_clean($link))] = $value;
      //           }
    		// }

    		$data['breadcrumb'] = $br;
    	}
    	return view($this->view . $view, $data);
    }

    public function route_clean($routes)
    {
    	$routes = explode('.', $routes); 
    	foreach ($routes as $i => $route) {
    		if(empty($route))
    			unset($routes[$i]);
    	}

    	return implode('.', $routes);
    }

    public function writeLog($activity) 
    {
        try {
            DB::beginTransaction();                        
            TpLogAktifitas::insert([
                'username' => Auth::user()->getUser()->username,
                'aktifitas' => $activity,
                'created_by' => 'DesktopBaseController',
                'created_at' => date('Y-m-d H:i:s'),
            ]);            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();            
            Log::error(__CLASS___ . ' >>> Gagal menyimpan log aktifitas');
            Log::error($e->getMessage());
        }
    }

    public function toJson($content)
    {
        return response()->json($content);
    }
}
