<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\DesktopBaseController;
use App\Models\TrPermission;
use DataTables;
use Auth;
use Log;
use DB;

class PermissionController extends DesktopBaseController
{
    protected $route = 'admin.permission.';

    public function __construct()
    {
    	parent::__construct();
    }

    public function index()
    {
        isAllow('show', 'permission');
    	$data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Admin' => true,
                'Permission' => route($this->route.'index'),
            ],
        ];
        return $this->view($this->route . 'index', $data);
    }

    public function getTable()
    {
        $permissions = DB::connection('sqlsrv')->select("select * from (
            select ROW_NUMBER() OVER(ORDER BY kode asc) AS rownum,  kode kode_group from laravel_sso.dbo.tr_groups g where g.id_application = 2
        ) a");

        return DataTables::of($permissions)
            ->addColumn('action', function ($permission) {
                $temp = ' <div style="text-align: center;"><a href="'.route($this->route . 'show', encrypt($permission->kode_group)).'" class="btn btn-alt-green btn-sm" title="Show Permissions"><i class="fa fa-lock"></i></a></div> ';
                return $temp;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        isAllow('show', 'permission');
        $kode_group = decrypt($id);   
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Admin' => true,
                'Permission' => route($this->route.'index'),
                'Details' => route($this->route.'create'),
            ],
            'rows' => DB::table('v_permissions')->where('kode_group', $kode_group)->get(),
            'title' => ucfirst(strtolower((str_replace('_', ' ', $kode_group)))),
            'kode_group' => $kode_group,
            'permissions' => [
                'show',
                'create',
                'store',
                'edit',
                'update',
                'execute',
                'delete',
            ]
        ];
        return $this->view($this->route . 'show', $data);
    }

    public function update(Request $request, $id)
    {
        $kode_group = decrypt($id);
        $groups = $request->except(['_token', '_method']);        
        $deleteQuery = '';
        $insertQuery = '';
        $rawSQL = '';
        $i = 0;
        foreach ($groups as $group => $permissions) {
            foreach($permissions as $role => $status) {
                if($i > 0) {         
                    $rawSQL .= "union select '" . $kode_group . "' as kode_group, " .
                    "'" . $group . "' as slug, " .
                    "'" . $role . "' as action "; 
                } else {
                    $rawSQL .= "select '" . $kode_group . "' as kode_group, " .
                    "'" . $group . "' as slug, " .
                    "'" . $role . "' as action "; 
                }
                $i++;
            }
        }
        // dd($rawSQL);
        
        /*$deleteQuery .= "delete p from tp_role_permissions p
        where p.kode_group = ?
        and not exists (
            select * from (".$rawSQL.") as a
            where a.kode_group = p.kode_group
            and a.slug = p.slug 
            and a.action = p.action
        )";*/

        $deleteQuery .= "delete p from tp_role_permissions p
        where p.kode_group = ?
        and not exists (
            select * from (".$rawSQL.") as a
            where a.kode_group = p.kode_group
            and a.slug = p.slug 
            and a.action = p.permission
        )";

        /*$insertQuery .= "insert tr_permissions (kode_group, slug, action, created_by, created_at)
        select kode_group, slug, action, 'admin' created_by, getdate() created_at from (".$rawSQL.") a
        where not exists (
        select * from tr_permissions p
        where p.kode_group = ?
        and a.kode_group = p.kode_group
        and a.slug = p.slug 
        and a.action = p.action
        )";*/
        $insertQuery .= "insert tp_role_permissions (kode_group, slug, permission, created_by, created_at)
        select kode_group, slug, action [permission], 'admin' created_by, getdate() created_at from (".$rawSQL.") a
        where kode_group = ? and not exists (
        select * from tp_role_permissions p where p.kode_group = a.kode_group and p.slug = a.slug and p.permission = a.[action]
        )";

        try {            
            DB::statement($deleteQuery, [$kode_group]);
            DB::statement($insertQuery, [$kode_group]);
            return redirect()->route($this->route . 'show', $id)->with('swalSuccess', 'Permission berhasil disimpan');
        } catch (QueryException $e) {
            Log::error("Error saat menyimpan perubahan permission");
            Log::error($e->getMessage());
            return redirect()->back()->with('swalError', 'Gagal meyimpan perubahan, mohon menghubungi Administrator');
        }
    }

    public function create(Request $request)
    {
        isAllow('create', 'permission');
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Admin' => true,
                'Permission' => route($this->route.'index'),
                'Detail' => route($this->route.'show', [$request->get('token')]),
                'Tambah' => route($this->route.'create'),
            ],
            'token' => $request->get('token')
        ];
        return $this->view($this->route . 'create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'slug' => 'required',
        ]);
        $data = [
            'slug' => $request->input('slug'),
            'kode_group' => decrypt($request->get('token')),
        ];

        try {
            DB::beginTransaction();
            TrPermission::insert([
                'kode_group' => $data['kode_group'],
                'slug' => $data['slug'],
                'action' => 'show',
                'created_by' => Auth::user()->getUser()->username,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            DB::commit();
            return redirect()->route($this->route.'show', [$request->get('token')])->with('swalSuccess', 'Permission berhasil disimpan');
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error("Error saat menyimpan permission");
            Log::error($e->getMessage());
            return redirect()->back()->with('swalError', 'Gagal meyimpan permission, mohon menghubungi Administrator');
        }
    }
}
