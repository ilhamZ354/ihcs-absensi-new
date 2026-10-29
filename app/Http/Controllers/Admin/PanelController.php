<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;

class PanelController extends DesktopBaseController
{
	protected $route = 'admin.panel.';

    public function __construct()
    {
    	parent::__construct();
    }

    public function index()
    {
    	$data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Admin' => true,
                'Panel' => route($this->route.'index'),
            ],
        ];
        return $this->view($this->route . 'index', $data);
    }
}
