<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Novelunitlessons;
use App\Models\Novelunitlist;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NovelunitlessonsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Novelunitlessons::with('novel_unit_lists')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('unit_name', function ($row) {
                    $unitListName = $row->novel_unit_lists?->name ?? 'No Unit List';
                    $unitName = $row->novel_unit_lists->novel->unit_name ?? 'No Unit Name';
                    $gradeName = optional($row->novel_unit_lists->novel->grades_category->grade)->name ?? 'No Grade';

                    return "{$unitListName} - {$unitName} - {$gradeName}";
                })


                ->addColumn('action', function ($row) {
                    $editUrl = route('novelunitlessons.edit', $row->id);
                    $deleteUrl = route('novelunitlessons.destroy', $row->id);

                    $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';
                    $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                        csrf_field() . method_field('DELETE') . '</form>';
                    $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                    return $editBtn . ' ' . $deleteForm . $deleteBtn;
                })
                ->rawColumns(['action', 'unit_name'])
                ->make(true);
        }

        return view('backend.novelunitlessons.index');
    }

    public function store(Request $request)
    {

        $request->validate([
            'novel_unit_list_id' => 'required|exists:novelunitlists,id',
            'name' => 'required|string|max:255',
        ]);

        $lesson = new Novelunitlessons();
        $lesson->novel_unit_list = $request->novel_unit_list_id;
        $lesson->name = $request->name;
        $lesson->save();

        return redirect()->back()->with('success', 'Lesson added successfully.');
    }

    public function edit($id)
    {
        $novelunitlesson = Novelunitlessons::findOrFail($id);


        return view('backend.novelunitlessons.edit', compact('novelunitlesson'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'novel_unit_list_id' => 'required|exists:novelunitlists,id',
            'name' => 'required|string|max:255',
        ]);

        $lesson = Novelunitlessons::findOrFail($id);
        $lesson->novel_unit_list = $request->novel_unit_list_id;
        $lesson->name = $request->name;
        $lesson->save();

        return redirect()->route('novelunitlessons.index')->with('success', value: 'Lesson updated successfully.');
    }

    public function destroy($id)
    {
        $lesson = Novelunitlessons::findOrFail($id);
        $lesson->delete();

        return redirect()->route('novelunitlessons.index')->with('success', value: 'Lesson Deleted successfully.');
    }
}
