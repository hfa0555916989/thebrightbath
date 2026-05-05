<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SiteSettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.site-settings.index', compact('settings'));
    }

    public function updateVisual(Request $request)
    {
        $keys = ['color_primary','color_gold','color_gold_deep','color_gold_light','color_orange','color_dark','color_bg','color_text','color_text_muted','color_border','font_primary','font_display'];
        $this->saveKeys($request, $keys);
        return back()->with('success', 'تم حفظ إعدادات الهوية البصرية بنجاح.');
    }

    public function updateContact(Request $request)
    {
        $keys = ['phone','whatsapp','whatsapp_message','email','address','working_hours'];
        $this->saveKeys($request, $keys);
        return back()->with('success', 'تم حفظ معلومات التواصل بنجاح.');
    }

    public function updateSocial(Request $request)
    {
        $keys = ['social_twitter','social_instagram','social_linkedin'];
        $this->saveKeys($request, $keys);
        return back()->with('success', 'تم حفظ روابط السوشيال ميديا بنجاح.');
    }

    public function updateSeo(Request $request)
    {
        $keys = ['site_name','site_tagline','ga_id','og_description'];
        $this->saveKeys($request, $keys);
        return back()->with('success', 'تم حفظ إعدادات SEO بنجاح.');
    }

    public function updateFooter(Request $request)
    {
        $keys = ['footer_description','copyright_text','newsletter_title','newsletter_subtitle'];
        $this->saveKeys($request, $keys);
        return back()->with('success', 'تم حفظ إعدادات الفوتر بنجاح.');
    }

    public function updateHero(Request $request)
    {
        $keys = ['hero_badge','hero_title_1','hero_gold_word','hero_title_2','hero_cta_primary','hero_cta_secondary','home_cta_title','home_about_title','home_about_body'];
        $this->saveKeys($request, $keys);

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('site', 'public');
            SiteSetting::set('hero_image', $path);
        }
        if ($request->hasFile('home_cta_image')) {
            $path = $request->file('home_cta_image')->store('site', 'public');
            SiteSetting::set('home_cta_image', $path);
        }

        return back()->with('success', 'تم حفظ إعدادات Hero بنجاح.');
    }

    public function updateAboutPage(Request $request)
    {
        $keys = ['about_hero_title','about_hero_subtitle','about_story_p1','about_story_p2','about_story_p3'];
        $this->saveKeys($request, $keys);

        if ($request->hasFile('about_hero_image')) {
            $path = $request->file('about_hero_image')->store('site', 'public');
            SiteSetting::set('about_hero_image', $path);
        }
        if ($request->hasFile('about_story_image')) {
            $path = $request->file('about_story_image')->store('site', 'public');
            SiteSetting::set('about_story_image', $path);
        }

        return back()->with('success', 'تم حفظ إعدادات صفحة "من نحن" بنجاح.');
    }

    public function updateVisionMission(Request $request)
    {
        $keys = ['vision_title','vision_text','mission_title','mission_text','mission_bullet1','mission_bullet2','mission_bullet3','mission_bullet4'];
        $this->saveKeys($request, $keys);

        if ($request->hasFile('vision_image')) {
            $path = $request->file('vision_image')->store('site', 'public');
            SiteSetting::set('vision_image', $path);
        }
        if ($request->hasFile('mission_image')) {
            $path = $request->file('mission_image')->store('site', 'public');
            SiteSetting::set('mission_image', $path);
        }

        return back()->with('success', 'تم حفظ الرؤية والرسالة بنجاح.');
    }

    public function updateContactPage(Request $request)
    {
        $keys = ['contact_hero_title','contact_hero_subtitle','contact_faq1_q','contact_faq1_a','contact_faq2_q','contact_faq2_a','contact_faq3_q','contact_faq3_a'];
        $this->saveKeys($request, $keys);
        return back()->with('success', 'تم حفظ إعدادات صفحة التواصل بنجاح.');
    }

    private function saveKeys(Request $request, array $keys): void
    {
        $data = [];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $data[$key] = $request->input($key);
            }
        }
        if (!empty($data)) {
            SiteSetting::setMany($data);
        }
    }
}
