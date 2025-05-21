<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
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

            $user = JWTAuth::user()->name;
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
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

        $user->otp = $otp;
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

        if ($user->otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP.'], 422);
        }
      

        if (Carbon::parse($user->otp_time)->diffInMinutes(now()) > 5) {
            return response()->json(['error' => 'OTP has expired. Please request a new one.'], 422);
        }

        $user->password = Hash::make($request->password);

        $user->otp = null;
        $user->otp_time = null;
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully.'
        ], 200);
    }
}
