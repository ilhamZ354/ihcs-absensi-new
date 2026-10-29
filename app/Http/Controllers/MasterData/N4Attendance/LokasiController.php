<?php

namespace App\Http\Controllers\MasterData\N4Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Attendance\Location;
use App\Models\Simkar\UnitBagianMaster as Unit;
use DataTables;

class LokasiController extends DesktopBaseController
{

    protected $route = 'master-data.n4attendance.lokasi.';

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
                'Lokasi' => route($this->route.'index'),
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
        return DataTables::of(Location::select('*'))
            ->editColumn('coordinates', function ($location) {
                return "<pre>" . json_encode(json_decode($location->coordinates)) . "</pre>";
            })
            ->addColumn('action', function ($location) {
                $temp = '';
                $temp .= ' <a href="javascript:;" class="btn btn-success btn-sm" onclick="showMap(event, \''.route($this->route . 'map', encrypt($location->id)).'\')" title="Tampilkan ' . $location->name.' dalam peta"><i class="fa fa-map"></i></a> ';

                if(allow('edit', 'kode-absensi')) {
                    $temp .= ' <a href="'.route($this->route . 'edit', encrypt($location->id)).'" class="btn btn-secondary btn-sm" title="Edit Lokasi"><i class="fa fa-edit"></i></a> ';
                } else {
                    $temp .= btn_disabled('edit');
                }
                if(allow('delete', 'kode-absensi')) {
                    $temp .= '<a href="javascript:;"class="btn btn-danger btn-sm" onclick="destroy(event, \''.route($this->route . 'destroy', encrypt($location->id)).'\')" title="Hapus Kode Absen"><i class="fa fa-trash"></i></a>';
                } else {
                    $temp .= btn_disabled('trash');
                }
                return $temp;
            })
            ->rawColumns(['status', 'coordinates', 'map', 'action'])
            ->make(true);
    }

    public function map($lokasiEncrypted)
    {
        $id = decrypt($lokasiEncrypted);
        
        $lokasi = Location::find($id);

        return $this->view($this->route . 'map', ['lokasi' => $lokasi]);
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
                'Location' => route($this->route.'index'),
                'Create' => true,
            ],
            'units' => Unit::select('kodeunitbagian as id', 'namaunitbagian as name')->where('status', '1')->orderBy('kodeunitbagian')->get()
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
            'unit' => 'required|string|max:2',
            'name' => 'required|string',
            'coordinates' => 'required|json',
        ]);

        $request->merge(['coordinates' => json_encode(json_decode($request->coordinates))]);

        Location::create($request->all());

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
                'Location' => route($this->route.'index'),
                'Create' => true,
            ],
            'units' => Unit::select('kodeunitbagian as id', 'namaunitbagian as name')->where('status', '1')->orderBy('kodeunitbagian')->get(),
            'location' => Location::find($id)
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

        $location = Location::find($id);

        $request->validate([
            'unit' => 'required|string|max:2',
            'name' => 'required|string',
            'coordinates' => 'required|json',
        ]);

        $request->merge(['coordinates' => json_encode(json_decode($request->coordinates))]);

        $location->update($request->all());

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
        return response()->json(Location::find($id)->delete());
    }
}
