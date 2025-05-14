<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class MainCategoryController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = MainCategory::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset($row->image) . '" width="100px" height="100px" />'
                        : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal"
                                data-id="' . $row->id . '"
                                data-name="' . $row->name . '"
                                data-image="' . ($row->image ? asset($row->image) : '') . '">Edit</button>';

                    $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . route('main-category.destroy', $row->id) . '" style="display:none;">' .
                        csrf_field() . method_field('DELETE') . '</form>';

                    $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-danger btn-sm">Delete</button>';

                    return $editBtn . ' ' . $deleteForm ;
                })
                ->rawColumns(['image', 'action'])
                ->make(mDataSupport: true);
        }

        return view("backend.category.index");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $category = new MainCategory();
            $category->name = $request->name;
            $category->slug =  Str::slug($category->name) . '-' . time();
            $category->image = upload_image($request, $category);
            $category->save();

            return redirect()->back()->with('success', 'Main Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $category = MainCategory::findOrFail($id);
            $category->name = $request->name;


            $category->image = upload_image($request, $category,$category->image);
            

            $category->save();

            return redirect()->back()->with('success', 'Main Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = MainCategory::findOrFail($id);

            if (!empty($category->image) && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            $category->delete();

            return redirect()->back()->with('success', 'Main Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}
