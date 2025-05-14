<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Country;
use App\Models\District;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
            $countries = Country::all();

            if ($request->ajax()) {
                $data = District::with('country'); // Eager load the country relationship

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Country', function ($row) {
                        return $row->country ? $row->country->name : 'No Country Assigned';
                    })
                    ->addColumn('Campus', function ($row) {
                        if ($row->campus && $row->campus->count()) {
                            return $row->campus->pluck('name')->implode(',');
                        }
                        return 'No Campus Assigned';
                    })
                    ->addColumn('action', function ($row) {
                        $editUrl = route('location.edit', $row->id);
                        $deleteUrl = route('location.destroy', $row->id);

                        $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';

                        $deleteForm = '<form id="delete-form-' . $row->id . '" method="POST" action="' . $deleteUrl . '" style="display:none;">' .
                            csrf_field() . method_field('DELETE') . '</form>';

                        $deleteBtn = '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>';

                        return '<div style="display: flex; gap: 8px;">' . $editBtn . $deleteBtn . '</div>' . $deleteForm;
                    })
                    ->rawColumns(['action', 'Country'])
                    ->make(true);
            }

            return view('backend.location.index', compact('countries'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        try {
            return view('backend.location.create');
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->route('location.index')->with('error', 'An error occurred while loading the create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'country_id' => 'required|exists:countries,id', // Validate country exists
            ]);

            $district = new District();
            $district->name = $request->name;
            $district->country_id = $request->country_id;
            $district->save();

            return redirect()->route('location.index')->with('success', 'District created successfully.');
        } catch (\Exception $e) {
            Log::error('Error storing district: ' . $e->getMessage());
            return redirect()->route('location.index')->with('error', 'An error occurred while creating the district.');
        }
    }

    public function campus_edit($id)
    {

        $campus = Campus::find($id);
        return view('backend.location.edit-campus', compact('campus'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $district = District::findOrFail($id);
            $countries = Country::all(); // Get countries for the dropdown
            return view('backend.location.edit', compact('district', 'countries'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->route('location.index')->with('error', 'An error occurred while loading the edit form.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'country_id' => 'required|exists:countries,id', // Ensure the selected district exists
            ]);

            $campus = District::findOrFail($id);

            $campus->name = $request->name;
            $campus->country_id = $request->country_id;

            $campus->save();

            return redirect()->route('location.index')->with('success', 'Campus updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating district: ' . $e->getMessage());
            return redirect()->route('location.index')->with('error', 'An error occurred while updating the campus.');
        }
    }

    /**
     * Update campus details.
     */
    public function update_campus(Request $request, $campusId = null)
    {
     
        // Common validation for create/update
        if ($request->method() !== 'DELETE') {
            $request->validate([
                'name' => 'required|string|max:255',
                'district_id' => 'required|exists:districts,id',
            ]);
        }
        try {
            if ($request->isMethod('delete')) {
                // DELETE logic
                if (!$campusId) {
                    return redirect()->route('location.index')->with('error', 'Campus ID required for deletion.');
                }

                $campus = Campus::find($campusId);
                if (!$campus) {
                    return redirect()->route('location.index')->with('error', 'Campus not found.');
                }

                $campus->delete();
                return redirect()->route('location.index')->with('success', 'Campus deleted successfully.');
            }

            if ($request->isMethod('PUT')) {
                // UPDATE logic
                $campus = Campus::find($campusId);
                if (!$campus) {
                    return redirect()->route('location.index')->with('error', 'Campus not found.');
                }

                $campus->name = $request->name;
                $campus->districts_id = $request->district_id;
                $campus->save();

                $message = 'Campus updated successfully.';
            } else {
                // CREATE logic
                $campus = new Campus();
                $campus->name = $request->name;
                $campus->districts_id = $request->district_id;
                $campus->save();

                $message = 'Campus created successfully.';
            }

            return redirect()->route('location.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error handling campus: ' . $e->getMessage());
            return redirect()->route('location.index')->with('error', 'Something went wrong.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $district = District::findOrFail($id);
            $district->delete();

            return redirect()->route('location.index')->with('success', 'District deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting district: ' . $e->getMessage());
            return redirect()->route('location.index')->with('error', 'An error occurred while deleting the district.');
        }
    }
}
