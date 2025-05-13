<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class GradeController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Grade::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return $row->image
                    ? '<img src="' . asset($row->image) . '" width="100px" height="100px" />'
                    : 'N/A';
            })
            ->addColumn('Category', function ($row) {
                return $row->grade ? $row->grade->name : 'No Grade Assigned';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('grades.edit', $row->id); // Fixed typo here
                $deleteUrl = route('grades.destroy', $row->id);

                $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';
                $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                    csrf_field() . method_field('DELETE') . '</form>';
                $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                return $editBtn . ' ' . $deleteForm . $deleteBtn;
            })
            ->rawColumns(['image', 'action', 'Category']) // Make sure 'Category' matches here
            ->make(true);
    }

    return view('backend.Grade.index');
}

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $grade = new Grade();
            $grade->name = $request->name;
            // $grade->main_cat_id = rand(min: 1,0);
            $grade->image = upload_image($request, $grade);
            $grade->save();

            return redirect()->back()->with('success', 'Grade created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        return view('backend.Grade.edit', compact('grade'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $grade = Grade::findOrFail($id);
            $grade->name = $request->name;
            // $grade->main_cat_id = $request->cat_id;

            $image = upload_image($request, $grade, $grade->image);
            if ($image) {
                $grade->image = $image;
            }

            $grade->save();

            return redirect()->route('grades.index')->with('success', 'Grade updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $grade = Grade::findOrFail($id);
            if ($grade->image && File::exists(public_path($grade->image))) {
                File::delete(public_path($grade->image));
            }

            $grade->delete();
            return redirect()->back()->with('success', 'Grade deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}
