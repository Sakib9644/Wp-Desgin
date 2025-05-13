<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Role::with('permissions')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('permissions', function ($role) {
                return $role->permissions->map(function ($permission) {
                    return '<span class="badge bg-success me-1">' . $permission->name . '</span>';
                })->implode(' ');
            })
            ->rawColumns(['permissions'])
            ->make(true);
    }

    $roles = Role::all(); // Optional: or just []
    $permissions = Permission::all();
    return view('backend.role.index', compact('roles', 'permissions'));
}

    public function permission($id)
    {
        $roles = Role::find( $id );
        $permissions = $roles->permissions;

        return response()->json([
            'permissions'=> $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
    
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'web';
        $role->save();
    
        return response()->json([ 'role' => $role]);
    }
    
    public function assignPermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
        ]);
    
        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permissions ?? []);

        $roles = Role::with('permissions')->get();
    
       
        // Return the HTML response directly
        return view('backend.helpers.permission',compact('roles'));
    }
    
}
