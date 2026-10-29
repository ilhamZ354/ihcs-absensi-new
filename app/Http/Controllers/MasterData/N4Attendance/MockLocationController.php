<?php

namespace App\Http\Controllers\MasterData\N4Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Attendance\MockLocation;
use DataTables;

class MockLocationController extends DesktopBaseController
{

    protected $route = 'master-data.n4attendance.mock.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Master Data' => true,
                'N4-Attendance' => true,
                'Mock Location' => true,
            ],
        ];
        return $this->view($this->route . 'index', $data);
    }

    /**
     * Display datatablses
     *
     * @return \Illuminate\Http\Response
     */
    public function getTable()
    {
        return DataTables::of(MockLocation::join('employees', 'employees.id', 'employee_id')->select('employees.name', 'mock_locations.created_at'))->make(true);
    }
}
