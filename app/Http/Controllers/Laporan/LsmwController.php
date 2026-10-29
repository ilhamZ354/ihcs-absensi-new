<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use Auth;

class LsmwController extends DesktopBaseController
{
  protected $route = 'laporan.lsmw.';

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $data = [
      'route' => $this->route,
      'breadcrumb' => ['Laporan' => true, 'LSMW' => route($this->route.'index')],
    ];

    return $this->view($this->route . 'index', $data);
  }

}
