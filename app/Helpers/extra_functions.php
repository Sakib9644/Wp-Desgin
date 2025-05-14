<?php

use App\Models\MainCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


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

        return $modelInstance->image; // Ensure a return value always exists
    }
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
