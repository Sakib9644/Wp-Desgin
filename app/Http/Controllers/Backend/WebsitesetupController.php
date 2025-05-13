<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetup;
use Exception;
use Illuminate\Support\Facades\File;

class WebsitesetupController extends Controller
{
    public function edit(){
        $data = WebsiteSetup::first();
        return view('backend.website-setting.admin',compact('data'));
    }


public function update(Request $request)
{
    $request->validate([
        'website_name' => 'required|string|max:255',
        'admin_footer' => 'nullable|string|max:255',
        'logo' => 'nullable|image|max:2048',
        'site_icon' => 'nullable|image|max:1024',
    ]);

    try {
        $websiteSetup = WebsiteSetup::firstOrCreate();

        $websiteSetup->website_name = $request->website_name;
        $websiteSetup->admin_footer = $request->admin_footer;

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Delete old logo
            if (!empty($websiteSetup->logo) && File::exists(public_path($websiteSetup->logo))) {
                File::delete(public_path($websiteSetup->logo));
            }

            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('/uploads/website/'), $logoName);
            $websiteSetup->logo = '/uploads/website/' . $logoName;
        }

        if ($request->hasFile('site_icon') && $request->file('site_icon')->isValid()) {
            // Delete old site icon
            if (!empty($websiteSetup->site_icon) && File::exists(public_path($websiteSetup->site_icon))) {
                File::delete(public_path($websiteSetup->site_icon));
            }

            $siteIcon = $request->file('site_icon');
            $siteIconName = 'site_icon_' . time() . '.' . $siteIcon->getClientOriginalExtension();
            $siteIcon->move(public_path('/uploads/website/'), $siteIconName);
            $websiteSetup->site_icon = '/uploads/website/' . $siteIconName;
        }

        $websiteSetup->save();

        return back()->with('success', 'Website settings updated successfully!');
    } catch (Exception $e) {
        return back()->with('error', 'Something went wrong. Please try again');
    }
}

}
