<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Novelunitlist;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NovelunitlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Novelunitlist::with('novel')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('unit_name', function ($row) {
                    $unitName = $row->novel?->unit_name ?? '';
                    $gradeName = $row->novel?->grades_category?->grade?->name ?? '';
                    return $unitName . ($gradeName ? " ({$gradeName})" : '');
                })

                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset($row->image) . '" width="100px" height="100px" />'
                        : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('novelunitdetails.edit', $row->id);
                    $deleteUrl = route('novelunitdetails.destroy', $row->id);

                    $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';
                    $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                        csrf_field() . method_field('DELETE') . '</form>';
                    $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                    return $editBtn . ' ' . $deleteForm . $deleteBtn;
                })
                ->rawColumns(['action', 'unit_name', 'image'])
                ->make(true);
        }

        return view('backend.novelunitlist.index');
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
            'novel_id' => 'required|exists:novels,id',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:8048',
        ]);

        $novelUnit = new NovelUnitList();
        $novelUnit->novel_id = $request->novel_id;
        $novelUnit->name = $request->name;
        $novelUnit->title = $request->title;
        $novelUnit->about = $request->about;
        $novelUnit->description = $request->description;
        $novelUnit->image = upload_image($request,  $novelUnit, $request->image);


        $novelUnit->save();

        return redirect()->back()->with('success', 'Novel Unit details created successfully.');
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
    $novelUnit = NovelUnitList::findOrFail($id);
    return view('backend.novelunitlist.edit', compact('novelUnit'));
}

public function update(Request $request, string $id)
{
    $request->validate([
        'novel_id' => 'required|exists:novels,id',
        'name' => 'required|string|max:255',
        'title' => 'nullable|string|max:255',
        'about' => 'nullable|string',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:8048',
    ]);

    $novelUnit = NovelUnitList::findOrFail($id);
    $novelUnit->novel_id = $request->novel_id;
    $novelUnit->name = $request->name;
    $novelUnit->title = $request->title;
    $novelUnit->about = $request->about;
    $novelUnit->description = $request->description;

    // Handle image upload (assuming your upload_image helper handles update too)
    if ($request->hasFile('image')) {
        $novelUnit->image = upload_image($request, $novelUnit, $request->image);
    }

    $novelUnit->save();

    return redirect()->route('novelunitdetails.index')->with('success', 'Novel Unit details updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
    {
        try {
            $novel = NovelUnitList::findOrFail($id);
            $novel->delete();

            return redirect()->back()->with('success', ' " '. $novel->name .'" deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}
