<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminCampusController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['school admin', 'Teacher']); // adjust if you're using role names
            })
                ->with('campus')
                ->select('id', 'name', 'email', 'campus_id')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('campus', function ($row) {
                    return $row->campus->name ?? 'No Campus Assigned';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        $campuses = Campus::select('id', 'name')->get();
        $schoolAdmins = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['school admin', 'Teacher']); // adjust if you're using role names
        })->select('id', 'name')->get();

        return view('backend.School-Admin-Campus.index', compact('campuses', 'schoolAdmins'));
    }
public function getDistricts(Request $request)
{
    $districts = District::where('country_id', $request->country_id)->get();
    return response()->json($districts);
}

public function getCampuses(Request $request)
{
    $campuses = Campus::where('districts_id', $request->district_id)->get();
    return response()->json($campuses);
}

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'campus_id' => 'required|exists:campuses,id',
        ]);

        try {
            $user = User::findOrFail($request->user_id);

            // Check if this campus is already assigned to a School Admin
            $assignedAdmin = User::where('campus_id', $request->campus_id)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'School Admin');
                })
                ->first();

            

            // If assigning a School Admin
            if ($user->hasRole('School Admin') && $assignedAdmin) {
                return redirect()->route('school-campus.index')
                    ->with('error', 'This Campus is already assigned to School Admin: ' . $assignedAdmin->name);
            }


            // If not already assigned, assign the campus
            $user->campus_id = $request->campus_id;
            $user->save();

            return redirect()->route('school-campus.index')
                ->with('success', 'User assigned to campus successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while assigning the campus: ' . $e->getMessage());
        }
    }
}
