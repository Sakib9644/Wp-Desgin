<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Country;
use App\Models\District;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // 🔐 Login method
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Get authenticated user
            $user = JWTAuth::user();

            // Check if OTP (email) is verified
            if (is_null($user->email_verified_at)) {
                return response()->json([
                    'error' => 'Please verify your OTP before logging in.'
                ], 403); // 403: Forbidden
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'message' => 'Login successful ✅',
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user->name,
        ]);
    }



    // 🔁 Request password reset (send OTP)
    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'No user found with this email address.'], 404);
        }

        $otp = rand(100000, 999999);

        $user->password_otp = $otp;
        $user->otp_time = now();
        $user->save();

        password_update($request->email, $otp);

        return response()->json([
            'message' => 'OTP sent to your email address. It is valid for 5 minutes.',
            'otp' => $otp,
        ], 200);
    }


    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'otp' => ['required'],
            'password' => ['required', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'No user found with this email address.'], 404);
        }

        if ($user->password_otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP.'], 422);
        }


        if (Carbon::parse($user->otp_time)->diffInMinutes(now()) > 5) {
            return response()->json(['error' => 'OTP has expired. Please request a new one.'], 422);
        }

        $user->password = Hash::make($request->password);

        $user->password_otp = null;
        $user->otp_time = null;
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully.'
        ], 200);
    }



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->otp = rand(100000, 999999);
            $user->password = Hash::make($request->password);
            $user->save();

            verify_otp($user->email, $user->otp);

            return response()->json([
                'message' => 'Registration successful. A verification OTP has been sent to your email. Please verify it to proceed.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'User registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function resend_otp(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->save();

        verify_otp($user->email, $otp);

        return response()->json([
            'message' => 'A new OTP has been sent to your email.',
        ], 200);
    }


    public function register_verify_otp(Request $request)
    {
        // Step 1: Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required', // you can validate length if OTP is 6 digits
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        // Step 3: Check OTP correctness
        if ($user->otp != $request->otp) {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }

        // Step 4: Mark OTP verified
        $user->email_verified_at = now();
        $user->otp = null; // or regenerate OTP if needed
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'OTP verified successfully. You are now logged in.',
            'token' => $token,
        ], 200);
    }
    public function country(Request $request)
    {
        $country = Country::all('id', 'name');
        return response()->json([$country], 200);
    }
    public function district($id)
    {
        $district = District::where('country_id', $id)->get();
        $districts = [];

        foreach ($district as $value) {
            $districts[] = [
                'id' => $value->id,
                'name' => $value->name,
            ];
        }

        return response()->json($districts, 200);
    }
    public function campus($id)
    {
        $district = Campus::where('districts_id', $id)->get();
        $districts = [];

        foreach ($district as $value) {
            $districts[] = [
                'id' => $value->id,
                'name' => $value->name,
            ];
        }

        return response()->json($districts, 200);
    }

    public function confirm(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'campus_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = JWTAuth::user();

            $user->campus_id = $request->input('campus_id');
            $user->status = false;
            $user->assignRole('Teacher');
            $user->save();

            return response()->json([
                'message' => 'User confirmed successfully.',
                'user' => $user,
                'role' => $user->getRoleNames()->first(), // Returns 'admin'

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function gradeaccess(Request $request)
    {
        $user = JWTAuth::user();

        $grades = $user->grades()->get()->map(function ($grade) {
            return [
                'id' => $grade->id,
                'name' => $grade->name,
                'image' => url($grade->image), // full URL like https://yourdomain.com/uploads/grades/...
            ];
        });

        return response()->json( $grades, 200);
    }
}
