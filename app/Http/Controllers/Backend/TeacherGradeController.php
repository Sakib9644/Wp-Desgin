<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\TecaherGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Nakanakaii\Countries\Countries;
use Nnjeim\World\World;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;

class TeacherGradeController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TecaherGrade::whereHas('user', function ($query) {
                $query->where('status', true);
            })
                ->with(['user', 'grade'])
                ->select('tecaher_grades.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', fn($row) => $row->user->name ?? '')
                ->addColumn('grade_name', fn($row) => $row->grade->name ?? '')
                ->addColumn('created_at', fn($row) => $row->created_at ? $row->created_at->format('Y-m-d') : '')
                ->addColumn('action', function ($row) {
                    $deleteUrl = route('teachergrades.destroy', $row->id);
                    $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                        csrf_field() . method_field('DELETE') . '</form>';
                    $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';
                    return $deleteForm . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        if (auth()->user()->hasRole('Super Admin')) {
            $users = Cache::remember('active_teachers', now()->addMinutes(10), function () {
                return User::where('status', true)
                    ->whereHas('roles', fn($query) => $query->where('name', 'teacher'))
                    ->get();
            });
        } else {
            $campus = auth()->user()->campus;
            $users = $campus && $campus->user ? $campus->user->where('status', true) : collect();
        }


        $grades = Grade::all();

        return view('backend.grade.teacheracess.index', compact('users', 'grades'));
    }

    public function show(Request $request)
    {
        $userId = $request->user_id;

        // Fetch grades assigned to this user
        $teacherGrades = TecaherGrade::where('user_id', $userId)->pluck('grade_id');
        $grades1 = Grade::whereIn('id', $teacherGrades)->pluck('id')->toArray();

        return view('backend.helpers.grade', compact('grades1'));
    }

    public function destroy($id)
    {

        $taecher = TecaherGrade::findOrFail($id);
        $taecher->delete();
        return redirect()->back()->with('success', 'Teacher Grade(s) deleted successfully');
    }

    public function store(Request $request)
    {
        // Validate the incoming request (optional but recommended)
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'grade_id' => 'required|array',
            'grade_id.*' => 'exists:grades,id',
        ]);

        TecaherGrade::where('user_id', $request->user_id)->delete();

        foreach ($request->grade_id as $grade_id) {
            $grade = new TecaherGrade();
            $grade->user_id = $request->user_id;
            $grade->grade_id = $grade_id;
            $grade->save();
        }

        return redirect()->back()->with('success', 'Teacher Grade(s) Created/Updated successfully');
    }
}
