<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{


public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Permission::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('permissions.edit', $row->id);
                $deleteUrl = route('permissions.destroy', $row->id);

                $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';

                $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                    csrf_field() . method_field('DELETE') . '</form>';

                $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                return $editBtn . ' ' . $deleteForm . $deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('backend.permissions.index');
}



    public function create()
    {
        return view('backend.permissions.create');
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }


    public function show(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('backend.permissions.show', compact('permission'));
    }

    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('backend.permissions.edit', compact('permission'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
