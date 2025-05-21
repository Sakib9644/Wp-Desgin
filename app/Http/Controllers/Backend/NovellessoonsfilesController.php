<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Novellessoonsfiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class NovellessoonsfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Novellessoonsfiles::with('lesson')->select('novellessoonsfiles.*');

        return DataTables::of($data)
         ->addColumn('lesson_name', function ($row) {
    $lessonName = $row->lesson->name ?? 'N/A';
    $unitListName = $row->lesson->novel_unit_lists?->name ?? 'No Unit List';
    $unitName = $row->lesson->novel_unit_lists?->novel->unit_name ?? 'No Unit Name';
    $gradeName = optional($row->lesson->novel_unit_lists?->novel->grades_category->grade)->name ?? 'No Grade';

    return "{$lessonName} - {$unitListName} - {$unitName} - {$gradeName}";
})

            ->addColumn('type', function ($row) {
                return ucfirst($row->type);
            })
            ->addColumn('file', function ($row) {
                if (!$row->file) {
                    return 'N/A';
                }

                // Use storage path if file stored via storage
                $filePath = asset(  $row->file);

                $extension = strtolower(pathinfo($row->file, PATHINFO_EXTENSION));
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                if (in_array($extension, $imageExtensions)) {
                    return '<img src="' . $filePath . '" width="100" height="100" />';
                }

                $iconMap = [
                    'pdf'  => 'https://cdn-icons-png.flaticon.com/512/337/337946.png',
                    'ppt'  => 'https://cdn-icons-png.flaticon.com/512/888/888879.png',
                    'pptx' => 'https://cdn-icons-png.flaticon.com/512/888/888879.png',
                    'xls'  => 'https://cdn-icons-png.flaticon.com/512/732/732220.png',
                    'xlsx' => 'https://cdn-icons-png.flaticon.com/512/732/732220.png',
                    'doc'  => 'https://cdn-icons-png.flaticon.com/512/888/888859.png',
                    'docx' => 'https://cdn-icons-png.flaticon.com/512/888/888859.png',
                ];

                $iconUrl = $iconMap[$extension] ?? 'https://cdn-icons-png.flaticon.com/512/833/833524.png';

                return '<a href="' . $filePath . '" target="_blank" title="Open File">' .
                    '<img src="' . $iconUrl . '" width="32" height="32" alt="' . strtoupper($extension) . ' Icon" />' .
                    '</a>';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                // Make sure the route name exists; update if needed
                $route = route('lesson.status.update', $row->id);

                // Wrap checkbox inside a form with a hidden input for unchecked value
                return '
                    <form method="POST" action="' . $route . '" class="status-form">
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
                $editUrl = route('novellessoonsfiles.edit', $row->id);
                $deleteUrl = route('novellessoonsfiles.destroy', $row->id);

                $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';
                $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                    csrf_field() . method_field('DELETE') . '</form>';
                $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                return $editBtn . ' ' . $deleteForm . $deleteBtn;
            })
            ->rawColumns(['action', 'file', 'status'])
            ->make(true);
    }

    return view('backend.novellessoonsfiles.index');
}


    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:novelunitlessons,id',
            'type' => 'required|in:teacher edition,edition,powerpoint,worksheet',
            'image' => 'required|file|max:50480', // max 20MB, adjust as needed
        ]);

        $novelFile = new Novellessoonsfiles();
        $novelFile->lesson_id = $request->lesson_id;
        $novelFile->type = $request->type;
        $novelFile->file = upload_image($request,  $novelFile, $request->image);
        $novelFile->status = true;
        $novelFile->save();

        return redirect()->route('novellessoonsfiles.index')->with('success', 'Novel lesson file uploaded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $novellessoonsfiles = Novellessoonsfiles::findOrFail($id);
        return view('backend.novellessoonsfiles.edit', compact('novellessoonsfiles',));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $novelFile = Novellessoonsfiles::findOrFail($id);

        $request->validate([
            'lesson_id' => 'required|exists:novelunitlessons,id',
            'type' => 'required|in:teacher edition,powerpoint,worksheet',
            'image' => 'nullable|file|max:50480', // same as store, but nullable
        ]);

        $novelFile->lesson_id = $request->lesson_id;
        $novelFile->type = $request->type;

        if ($request->hasFile('image')) {
            // Delete old file if exists
            if ($novelFile->file && Storage::disk('public')->exists($novelFile->file)) {
                Storage::disk('public')->delete($novelFile->file);
            }
            // Use the same upload_image helper
            $novelFile->file = upload_image($request, $novelFile, $request->image);
        }

        $novelFile->save();

        return redirect()->route('novellessoonsfiles.index')->with('success', 'Novel lesson file updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $novelFile = Novellessoonsfiles::findOrFail($id);

            if ($novelFile->file && Storage::disk('public')->exists($novelFile->file)) {
                Storage::disk('public')->delete($novelFile->file);
            }

            $novelFile->delete();

            return redirect()->route('novellessoonsfiles.index')->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('novellessoonsfiles.index')->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}
