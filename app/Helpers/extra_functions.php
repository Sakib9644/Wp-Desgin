<?php

use App\Mail\PasswordReset;
use App\Mail\register_verify_otp;
use App\Models\MainCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


if (!function_exists('upload_image')) {
    function upload_image($request, $modelInstance, $file = null) {
      
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if (!empty($file) && File::exists(public_path($file))) {
                File::delete(public_path($file));
            }

            $folderName = $modelInstance->getTable(); // Fixed
            $thumbnail = $request->file('image');
            $thumbnailName = $request->name . '_' . time() . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move(public_path('/uploads/' . $folderName . '/'), $thumbnailName);

            return '/uploads/' . $folderName . '/' . $thumbnailName;
        }
         

        return $file; 
    }
}

    function password_update($to,$otp)
    {
        
        $from = env('MAIL_FROM_ADDRESS');
        $subject = 'Password Reset Request';

       
        
    // dd($from,$to,$cc);
        Mail::to($to)
            ->send(new PasswordReset($from,  $subject,$otp, $to));
    }
    function verify_otp($to,$otp)
    {
        
        $from = 'demo@gmail.com';
        $subject = 'Register Otp Request';

       
        
        Mail::to($to)
            ->send(new register_verify_otp($from,  $subject,$otp, $to));
    }

if (!function_exists('slug')) {
    function slug($name, $user) {
        $slug = Str::slug($name);

        if(MainCategory::where('slug', $slug )->exists()) {
            $slug  = $slug = Str::slug($name) . '-' . time();



        }
        return $slug;
    }
}
