<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogoController extends Controller
{
    public function index()
    {
        return view('admin.logo-upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'logo_white' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:1024',
            'og_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
        ]);

        $uploaded = false;

        // Ensure images directory exists
        $imagesPath = public_path('images');
        if (!File::isDirectory($imagesPath)) {
            File::makeDirectory($imagesPath, 0755, true);
        }

        // Main Logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logo->move($imagesPath, 'logo.png');
            $uploaded = true;
        }

        // White Logo
        if ($request->hasFile('logo_white')) {
            $logoWhite = $request->file('logo_white');
            $logoWhite->move($imagesPath, 'logo-white.png');
            $uploaded = true;
        }

        // Favicon
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $favicon->move(public_path(), 'favicon.png');
            $uploaded = true;
        }

        // OG Image
        if ($request->hasFile('og_image')) {
            $ogImage = $request->file('og_image');
            $ogImage->move($imagesPath, 'og-image.jpg');
            $uploaded = true;
        }

        if ($uploaded) {
            return back()->with('success', 'تم رفع الشعار بنجاح!');
        }

        return back()->with('error', 'لم يتم اختيار أي ملف للرفع');
    }
}


