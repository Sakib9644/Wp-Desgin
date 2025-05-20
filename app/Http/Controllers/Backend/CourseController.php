<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Coursesyllabus;
use App\Models\Novellessoonsfiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $courses = Course::with('gradecategory')->select(['id', 'status', 'file', 'grade_category']);

            return DataTables::of($courses)
                ->addIndexColumn()

                ->addColumn('grade_category', function ($row) {
                    if ($row->gradecategory && $row->gradecategory->grade) {
                        return $row->gradecategory->name . ' (' . $row->gradecategory->grade->name . ')';
                    } elseif ($row->gradecategory) {
                        return $row->gradecategory->name . ' (No Grade)';
                    }
                    return 'No Category';
                })

                ->addColumn('file', function ($row) {
                    if ($row->file) {
                        return '<a href="' . asset($row->file) . '" target="_blank" class="btn btn-sm btn-primary">View PDF</a>';
                    }
                    return 'No File';
                })


                ->addColumn('status', function ($row) {
                    $checked = $row->status ? 'checked' : '';
                    $form = '
        <form method="POST" action="' . route('course.status.update', $row->id) . '" class="status-form">
            ' . csrf_field() . '
            <input type="hidden" name="status" value="0">
            <div class="form-check form-switch">
                <input class="form-check-input toggle-status" type="checkbox" name="status_toggle" data-id="' . $row->id . '" ' . $checked . '>
            </div>
        </form>
    ';
                    return $form;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('course.edit', $row->id);
                    $deleteUrl = route('course.destroy', $row->id);

                    $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';

                    $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                        csrf_field() . method_field('DELETE') . '</form>';

                    $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                    return $editBtn . ' ' . $deleteForm . $deleteBtn;
                })



                ->rawColumns(['file', 'status', 'action']) // Correct column names that return HTML
                ->make(true);
        }

        return view('backend.course.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function updateStatus(Request $request, $id)
    {
        if(Route::is('lesson.status.update')){
        $course = Novellessoonsfiles::findOrFail($id);
        $course->status = $request->status;
        $course->save();

        $statusMessage = $course->status ? 'Activated' : 'Deactivated';

        return redirect()->back()->with('success', 'Status "' . $statusMessage . '" successfully.'); 
        }
        else{
            $course = Course::findOrFail($id);
        $course->status = $request->status;
        $course->save();

        $statusMessage = $course->status ? 'Activated' : 'Deactivated';

        return redirect()->back()->with('success', 'Status "' . $statusMessage . '" successfully.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grade_category' => 'required',
            'image' => 'required|mimes:pdf',
        ]);
        try {

            $course = new Course();
            $course->grade_category = $request->grade_category;
            $course->status = 1;
            $course->file = upload_image($request, $course, $course->image);
            $course->save();

            return redirect()->back()->with('success', 'Course and syllabuses created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $course = Course::findOrFail($id);
        return view('backend.course.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {

            $course =  Course::find($id);
            $course->grade_category = $request->grade_category;
            $course->status = 1;
            $course->file = upload_image($request, $course, $course->file);
            $course->save();

            return redirect()->route('course.index')->with('success', 'Course and syllabuses Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course =  Course::find($id);
         if ($course->file && file_exists(public_path($course->file))) {
        unlink(public_path($course->file));
    }
        $course->delete();
        return redirect()->route('course.index')->with('success', 'Course and syllabuses Deleted successfully.');
    }
}
