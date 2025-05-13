<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_name' => 'required|string',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->syncRoles([$request->role_name]);

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }


    public function admin_create(Request $request)
    {
        if ($request->ajax()) {
    $data = User::with('roles')->select('users.*');

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('role', function ($user) {
            return $user->getRoleNames()->first();
        })
        ->addColumn('action', function ($user) {
            // Edit Button - Link to external edit page
            $editBtn = '<a href="' . route('user.edit', $user->id) . '" class="btn btn-sm btn-warning">Edit</a>';

            // Delete Button
            $deleteForm = '<form id="delete-form-' . $user->id . '" method="POST" action="' . route('user.destroy', $user->id) . '" style="display:inline-block;">' . 
                          csrf_field() . method_field('DELETE') . 
                          '</form>';

            $deleteBtn = '<button onclick="confirmDelete(' . $user->id . ')" class="btn btn-sm btn-danger">Delete</button>';

            return $editBtn . ' ' . $deleteForm . ' ' . $deleteBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
}



        // Return the user creation form view
        return view('backend.role.user_create');
    }
    public function edit($id) {
        $user = User::findOrFail($id);
        return view('backend.role.user_edit', compact('user'));
    }

    public function admin_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role_name' => 'required|exists:roles,name'
        ]);
        try {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->assignRole($request->role_name);
            $user->save();

            return redirect()->back()->with('success', 'User created successfully');
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while creating the user' . $e->getMessage());
        }
    }
}
