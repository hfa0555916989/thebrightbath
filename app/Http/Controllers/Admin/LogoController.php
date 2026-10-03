<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    /**
     * Form field => site setting key read by site_image() in the views.
     */
    private const IMAGES = [
        'logo' => 'site_logo',
        'logo_white' => 'site_logo_white',
        'favicon' => 'site_favicon',
        'og_image' => 'site_og_image',
    ];

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

        foreach (self::IMAGES as $field => $settingKey) {
            if ($request->hasFile($field)) {
                delete_upload(setting($settingKey));
                SiteSetting::set($settingKey, store_upload($request->file($field), 'site'));
                $uploaded = true;
            }
        }

        if ($uploaded) {
            return back()->with('success', 'تم رفع الشعار بنجاح!');
        }

        return back()->with('error', 'لم يتم اختيار أي ملف للرفع');
    }
}
