<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $agent;

    public function __construct()
    {
        $this->middleware('auth');
        $this->agent = new Agent();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!$this->agent->isMobile()) {
            return view('desktop.home');
        // }
        // else {
            // return view('mobile.home');
            // return "<h2>Silahkan akses aplikasi melalui perangkat komputer Desktop/Laptop</h2>";
        // }
    }

}
