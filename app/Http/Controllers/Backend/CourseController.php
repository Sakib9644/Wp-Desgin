<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Coursesyllabus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $courses = Course::select(['id', 'image', 'name']); // select only needed fields

            return DataTables::of($courses)
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset($row->image) . '" height="50" />';
                })
                ->addColumn('pdf', function ($row) {
                    $url = route('course.show', $row->id); // Pass the course ID or appropriate param
                    return '<a href="' . $url . '" target="_blank" class="btn btn-info">View PDF</a>';
                })
                ->rawColumns(['image','pdf']) // Allow HTML rendering for the image column
                ->make(true);
        }

        return view('backend.course.index'); // Your Blade view
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf' => 'required|array',
            'pdf.*' => 'mimes:pdf',
        ]);



        $course = Course::firstOrNew();
        $course->name = $request->name;
        $course->image = upload_image($request, $course,       $course->image);
        $course->save();


        // Loop through and save each PDF in course_syllabuses
        if ($request->hasFile('pdf')) {
            foreach ($request->file('pdf') as $pdfFile) {
                if ($pdfFile->isValid()) {
                    $syllabus = new CourseSyllabus();
                    $syllabus->course_id = $course->id;

                    // Create unique filename for each PDF
                    $pdfName = 'syllabus_' . time() . '_' . uniqid() . '.' . $pdfFile->getClientOriginalExtension();
                    $pdfPath = '/uploads/course/syllabus/' . $pdfName;

                    // Move file to public/uploads/course/syllabus
                    $pdfFile->move(public_path('/uploads/course/syllabus/'), $pdfName);

                    $syllabus->file = $pdfPath;
                    $syllabus->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Course and syllabuses created successfully.');
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
