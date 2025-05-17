<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Gradecategory;
use Illuminate\Http\Request;

class GradecategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view("backend.gradecategory.index");
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
     */public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'grade_id' => 'required|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048',
       
    ]);
    try {

        $course = new Gradecategory();
        $course->name = $request->name;
        $course->garde_id = $request->grade_id; // Note: Typo - should be 'grade_id'
        $course->image = upload_image($request, $course, $course->image);
        $course->save();


        return redirect()->back()->with('success', 'Grade Category created successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
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
    public function edit($id)
{
    $category = Gradecategory::findOrFail($id);
    $grades = Grade::all();

    return view('backend.gradecategory.edit', compact('category', 'grades'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'grade_id' => 'required|exists:grades,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $category = Gradecategory::findOrFail($id);
    $category->name = $request->name;
    $category->garde_id = $request->grade_id;

    if ($request->hasFile('image')) {
        // Optionally delete the old image
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $image = $request->file('image');
        $imageName = 'category_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/gradecategory'), $imageName);
        $category->image = 'uploads/gradecategory/' . $imageName;
    }

    $category->save();

    return redirect()->route('gradecategory.index')->with('success', 'Category updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    $category = Gradecategory::findOrFail($id);

    // Optionally delete image
    if ($category->image && file_exists(public_path($category->image))) {
        unlink(public_path($category->image));
    }

    $category->delete();

    return redirect()->back()->with('success', 'Category deleted successfully.');
}

}
