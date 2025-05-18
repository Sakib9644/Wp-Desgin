<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\GradeCategory; // Make sure this model exists and is related properly
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NovelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Novel::with('grades_category')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Grade Category', function ($row) {
                    return $row->grades_category?->name . ' (' . ($row->grades_category?->grade?->name ?? 'No Grade') . ')' ?? 'No Grade Category Assigned';
                })
                  ->addColumn('action', function ($row) {
                $editUrl = route('novel.edit', $row->id); // Fixed typo here
                $deleteUrl = route('novel.destroy', $row->id);

                $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';
                $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                    csrf_field() . method_field('DELETE') . '</form>';
                $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                return $editBtn . ' ' . $deleteForm . $deleteBtn;
            })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.novel.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gradeCategories = GradeCategory::all();
        return view('backend.novel.create', compact('gradeCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grade_category_id' => 'required|exists:gradecategories,id',
            'unit_name' => 'required|string|max:255',
        ]);

        try {


            $novel = new Novel();
            $novel->grade_category = $request->grade_category_id;
            $novel->unit_name = $request->unit_name;
            $novel->status = 1;

            $novel->save();

            return redirect()->back()->with('success', 'Novel created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $novel = Novel::with('grade_category')->findOrFail($id);
        return view('backend.novel.show', compact('novel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $novel = Novel::findOrFail($id);
        $gradeCategories = GradeCategory::all();
        return view('backend.novel.edit', compact('novel', 'gradeCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'grade_category_id' => 'required|exists:gradecategories,id',
            'unit_name' => 'required|string|max:255',
        ]);

        try {
            $novel = Novel::findOrFail($id);



            $novel->grade_category = $request->grade_category_id;
            $novel->unit_name = $request->unit_name;
            $novel->save();

            return redirect()->route('novel.index')->with('success', 'Novel updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $novel = Novel::findOrFail($id);
            $novel->delete();

            return redirect()->back()->with('success', 'Novel deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}
