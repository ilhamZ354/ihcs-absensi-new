<?php

namespace App\Http\Controllers\MasterData\N4Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Attendance\Location;
use App\Models\Attendance\EmployeeGroup;
use DataTables;

class EmployeeGroupController extends DesktopBaseController
{
    protected $route = 'master-data.n4attendance.groups.';

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
                'Groups' => route($this->route.'index'),
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
        return DataTables::of(EmployeeGroup::with('location')->select('*'))
            ->addColumn('location_', function ($group) {
                return '<a href="javascript:;" onclick="showMap(event, \''.route('master-data.n4attendance.lokasi.map', encrypt($group->location_id)).'\')">'.$group->location->name.'</a>';
            })
            ->addColumn('is_security', function ($group) {
                return $group->is_security 
                ? '<span class="badge badge-danger">Karyawan Satpam</span>' 
                : '<span class="badge badge-success">Karyawan Reguler</span>';
            })
            ->addColumn('action', function ($group) {
                $temp = '';
                if(allow('edit', 'kode-absensi')) {
                    $temp .= ' <a href="'.route($this->route . 'edit', encrypt($group->id)).'" class="btn btn-secondary btn-sm" title="Edit Group"><i class="fa fa-edit"></i></a> ';
                } else {
                    $temp .= btn_disabled('edit');
                }
                if(allow('delete', 'kode-absensi')) {
                    $temp .= '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($group->id)).'\')" title="Hapus Group"><i class="fa fa-trash"></i></a>';
                } else {
                    $temp .= btn_disabled('trash');
                }
                return $temp;
            })
            ->rawColumns(['status', 'is_security', 'location_', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Master Data' => true,
                'N4-Attendance' => true,
                'Groups' => route($this->route.'index'),
                'Create' => true,
            ],
            'locations' => Location::select('id', 'name')->get()
        ];
        return $this->view($this->route . 'create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|integer',
            'name' => 'required|string',
            'is_security' => 'required|boolean',
        ]);

        $request->merge(['location_id' => $request->location]);

        EmployeeGroup::create($request->except('location'));

        return redirect()->route($this->route . 'index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($idEncrypted)
    {
        $id = decrypt($idEncrypted);

        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Master Data' => true,
                'N4-Attendance' => true,
                'Groups' => route($this->route.'index'),
                'Edit' => true,
            ],
            'locations' => Location::select('id', 'name')->get(),
            'employeeGroup' => EmployeeGroup::find($id)
        ];
        return $this->view($this->route . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idEncrypted)
    {
        $id = decrypt($idEncrypted);

        $employeeGroup = EmployeeGroup::find($id);

        $request->validate([
            'location' => 'required|integer',
            'name' => 'required|string',
            'is_security' => 'required|boolean',
        ]);

        $request->merge(['location_id' => $request->location]);

        $employeeGroup->update($request->except('location'));

        return redirect()->route($this->route . 'index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idEncrypted)
    {
        $id = decrypt($idEncrypted);
        return response()->json(EmployeeGroup::find($id)->delete());
    }
}
