@extends('layouts.admin')

@section('title', 'إعدادات الموقع')

@section('content')
<div class="space-y-6" x-data="{ tab: '{{ session('active_tab', 'visual') }}' }">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">إعدادات الموقع</h2>
            <p class="text-brand-textMuted mt-1">تحكم في تصميم ومحتوى الموقع بالكامل</p>
        </div>
        <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
            <i class="fas fa-cog text-brand-goldDeep text-xl"></i>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2 border-b border-brand-border pb-0">
        @foreach([
            ['visual',       'fas fa-palette',    'الهوية البصرية'],
            ['contact',      'fas fa-phone-alt',  'معلومات التواصل'],
            ['social',       'fab fa-instagram',  'السوشيال ميديا'],
            ['seo',          'fas fa-search',     'SEO والتحليلات'],
            ['footer',       'fas fa-grip-lines', 'الفوتر والنشرة'],
            ['hero',         'fas fa-home',       'Hero الرئيسية'],
            ['about',        'fas fa-info-circle','صفحة "من نحن"'],
            ['vision',       'fas fa-eye',        'الرؤية والرسالة'],
            ['contact_page', 'fas fa-envelope',   'صفحة التواصل'],
        ] as [$id, $icon, $label])
        <button @click="tab = '{{ $id }}'"
            :class="tab === '{{ $id }}' ? 'border-brand-gold text-brand-gold bg-brand-gold/5' : 'border-transparent text-gray-500 hover:text-brand-dark hover:border-gray-300'"
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm transition whitespace-nowrap -mb-px">
            <i class="{{ $icon }}"></i> {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ===== TAB: الهوية البصرية ===== --}}
    <div x-show="tab === 'visual'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.visual') }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-6">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-palette text-brand-gold"></i> الألوان الرئيسية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach([
                        ['color_primary',   'اللون الأساسي (أزرق داكن)'],
                        ['color_gold',      'اللون الذهبي'],
                        ['color_gold_deep', 'الذهبي الداكن (hover)'],
                        ['color_gold_light','الذهبي الفاتح'],
                        ['color_orange',    'اللون البرتقالي'],
                        ['color_dark',      'اللون الداكن (navbar/footer)'],
                        ['color_bg',        'لون الخلفية العامة'],
                        ['color_text',      'لون النص الرئيسي'],
                        ['color_text_muted','لون النص الثانوي'],
                        ['color_border',    'لون الحدود'],
                    ] as [$key, $label])
                    <div x-data="{ c: '{{ $settings[$key] ?? '#000000' }}' }">
                        <label class="block text-sm font-medium text-brand-dark mb-2">{{ $label }}</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="{{ $key }}" x-model="c"
                                class="w-12 h-10 rounded-lg border border-brand-border cursor-pointer">
                            <input type="text" x-model="c" readonly
                                class="flex-1 px-3 py-2 border border-brand-border rounded-lg text-sm font-mono bg-gray-50">
                            <div class="w-10 h-10 rounded-lg border border-brand-border" :style="`background:${c}`"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr class="border-brand-border">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-font text-brand-gold"></i> الخطوط
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">الخط الرئيسي</label>
                        <input type="text" name="font_primary" value="{{ $settings['font_primary'] ?? 'Tajawal' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                        <p class="text-xs text-brand-textMuted mt-1">اسم خط Google Fonts</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">خط العناوين</label>
                        <input type="text" name="font_display" value="{{ $settings['font_display'] ?? 'Noto Kufi Arabic' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ الهوية البصرية
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: التواصل ===== --}}
    <div x-show="tab === 'contact'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.contact') }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-4">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-phone-alt text-brand-gold"></i> معلومات التواصل
                </h3>
                <p class="text-sm text-brand-textMuted bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <i class="fas fa-info-circle text-blue-500 ml-1"></i>
                    هذه المعلومات تظهر في الفوتر، صفحة التواصل، وأزرار الواتساب العائمة في جميع الصفحات.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([
                        ['phone',           'رقم الهاتف',              'text', '+966 54 349 4316'],
                        ['whatsapp',        'رقم الواتساب (بدون +)',   'text', '966543494316'],
                        ['email',           'البريد الإلكتروني',        'email','cs@thebrightbath.com'],
                        ['address',         'العنوان',                  'text', 'المملكة العربية السعودية'],
                        ['working_hours',   'ساعات العمل',             'text', 'الأحد - الخميس: 9 ص - 5 م'],
                    ] as [$key, $label, $inputType, $placeholder])
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">{{ $label }}</label>
                        <input type="{{ $inputType }}" name="{{ $key }}" value="{{ $settings[$key] ?? '' }}" placeholder="{{ $placeholder }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    @endforeach
                </div>
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">رسالة الواتساب الافتراضية</label>
                    <input type="text" name="whatsapp_message" value="{{ $settings['whatsapp_message'] ?? '' }}"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <p class="text-xs text-brand-textMuted mt-1">الرسالة التي تُرسل تلقائياً عند الضغط على زر الواتساب</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ معلومات التواصل
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: السوشيال ميديا ===== --}}
    <div x-show="tab === 'social'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.social') }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-4">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fab fa-instagram text-brand-gold"></i> روابط السوشيال ميديا
                </h3>
                <div class="space-y-4">
                    @foreach([
                        ['social_twitter',   'fab fa-twitter',    'رابط تويتر / X',  'https://twitter.com/...'],
                        ['social_instagram', 'fab fa-instagram',  'رابط إنستقرام',   'https://instagram.com/...'],
                        ['social_linkedin',  'fab fa-linkedin-in','رابط لينكدإن',     'https://linkedin.com/...'],
                    ] as [$key, $icon, $label, $placeholder])
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">
                            <i class="{{ $icon }} ml-1"></i> {{ $label }}
                        </label>
                        <input type="url" name="{{ $key }}" value="{{ $settings[$key] ?? '' }}" placeholder="{{ $placeholder }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20" dir="ltr">
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ روابط السوشيال
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: SEO ===== --}}
    <div x-show="tab === 'seo'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.seo') }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-4">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-search text-brand-gold"></i> SEO والتحليلات
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">اسم الموقع</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                        <p class="text-xs text-brand-textMuted mt-1">يظهر في تبويب المتصفح وبطاقات المشاركة</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">الشعار النصي</label>
                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">معرّف Google Analytics (GA4)</label>
                    <input type="text" name="ga_id" value="{{ $settings['ga_id'] ?? '' }}" placeholder="G-XXXXXXXXXX"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">وصف الموقع العام (OG Description)</label>
                    <textarea name="og_description" rows="3"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">{{ $settings['og_description'] ?? '' }}</textarea>
                    <p class="text-xs text-brand-textMuted mt-1">يظهر عند مشاركة رابط الموقع على السوشيال ميديا</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ إعدادات SEO
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: الفوتر ===== --}}
    <div x-show="tab === 'footer'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.footer') }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-4">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-grip-lines text-brand-gold"></i> الفوتر والنشرة البريدية
                </h3>
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">وصف الفوتر</label>
                    <textarea name="footer_description" rows="2"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">{{ $settings['footer_description'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">نص حقوق النشر</label>
                    <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] ?? '' }}"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <p class="text-xs text-brand-textMuted mt-1">السنة تُضاف تلقائياً</p>
                </div>
                <hr class="border-brand-border">
                <h4 class="font-bold text-brand-dark">النشرة البريدية</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">عنوان قسم النشرة</label>
                        <input type="text" name="newsletter_title" value="{{ $settings['newsletter_title'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">وصف النشرة</label>
                        <input type="text" name="newsletter_subtitle" value="{{ $settings['newsletter_subtitle'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ إعدادات الفوتر
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: Hero ===== --}}
    <div x-show="tab === 'hero'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.hero') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-6">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-home text-brand-gold"></i> قسم Hero - الصفحة الرئيسية
                </h3>

                {{-- Hero Image --}}
                <div x-data="{ preview: '' }">
                    <label class="block text-sm font-medium text-brand-dark mb-2">صورة الخلفية</label>
                    <div class="flex items-start gap-4">
                        <div class="w-48 h-28 bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-brand-border flex-shrink-0">
                            <img :src="preview || '{{ $settings['hero_image'] ?? '' }}'"
                                class="w-full h-full object-cover" x-show="preview || '{{ $settings['hero_image'] ?? '' }}'">
                            <div class="w-full h-full flex items-center justify-center" x-show="!preview && !'{{ $settings['hero_image'] ?? '' }}'">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="hero_image" accept="image/*"
                                @change="preview = URL.createObjectURL($event.target.files[0])"
                                class="w-full px-4 py-3 border border-brand-border rounded-lg">
                            <p class="text-xs text-brand-textMuted mt-2">أو أدخل رابط URL مباشرة:</p>
                            <input type="url" name="hero_image_url" value="{{ str_starts_with($settings['hero_image'] ?? '', 'http') ? $settings['hero_image'] : '' }}"
                                placeholder="https://..." dir="ltr"
                                class="w-full px-4 py-2 border border-brand-border rounded-lg text-sm mt-1">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">نص الشارة (Badge)</label>
                        <input type="text" name="hero_badge" value="{{ $settings['hero_badge'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">الكلمة الذهبية المميزة</label>
                        <input type="text" name="hero_gold_word" value="{{ $settings['hero_gold_word'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                        <p class="text-xs text-brand-textMuted mt-1">يجب أن تكون جزءاً من السطر الأول</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">العنوان - السطر الأول</label>
                        <input type="text" name="hero_title_1" value="{{ $settings['hero_title_1'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">العنوان - السطر الثاني</label>
                        <input type="text" name="hero_title_2" value="{{ $settings['hero_title_2'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">نص الزر الرئيسي</label>
                        <input type="text" name="hero_cta_primary" value="{{ $settings['hero_cta_primary'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">نص الزر الثانوي</label>
                        <input type="text" name="hero_cta_secondary" value="{{ $settings['hero_cta_secondary'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                </div>

                <hr class="border-brand-border">
                <h4 class="font-bold text-brand-dark">قسم CTA (أسفل الصفحة الرئيسية)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">عنوان قسم CTA</label>
                        <input type="text" name="home_cta_title" value="{{ $settings['home_cta_title'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                </div>

                <hr class="border-brand-border">
                <h4 class="font-bold text-brand-dark">قسم "من نحن" في الرئيسية</h4>
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">العنوان</label>
                    <input type="text" name="home_about_title" value="{{ $settings['home_about_title'] ?? '' }}"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">النص</label>
                    <textarea name="home_about_body" rows="3"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">{{ $settings['home_about_body'] ?? '' }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ إعدادات Hero
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: من نحن ===== --}}
    <div x-show="tab === 'about'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.about') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-4">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-info-circle text-brand-gold"></i> صفحة "من نحن"
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">العنوان الرئيسي</label>
                        <input type="text" name="about_hero_title" value="{{ $settings['about_hero_title'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">العنوان الفرعي</label>
                        <input type="text" name="about_hero_subtitle" value="{{ $settings['about_hero_subtitle'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                </div>

                {{-- About Hero Image --}}
                <div x-data="{ preview: '' }">
                    <label class="block text-sm font-medium text-brand-dark mb-2">صورة خلفية "من نحن"</label>
                    <div class="flex items-start gap-4">
                        <div class="w-48 h-28 bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-brand-border flex-shrink-0">
                            <img :src="preview || '{{ $settings['about_hero_image'] ?? '' }}'" class="w-full h-full object-cover"
                                x-show="preview || '{{ $settings['about_hero_image'] ?? '' }}'">
                        </div>
                        <input type="file" name="about_hero_image" accept="image/*"
                            @change="preview = URL.createObjectURL($event.target.files[0])"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg">
                    </div>
                </div>

                {{-- Story Image --}}
                <div x-data="{ preview: '' }">
                    <label class="block text-sm font-medium text-brand-dark mb-2">صورة قسم القصة</label>
                    <div class="flex items-start gap-4">
                        <div class="w-48 h-28 bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-brand-border flex-shrink-0">
                            <img :src="preview || '{{ $settings['about_story_image'] ?? '' }}'" class="w-full h-full object-cover"
                                x-show="preview || '{{ $settings['about_story_image'] ?? '' }}'">
                        </div>
                        <input type="file" name="about_story_image" accept="image/*"
                            @change="preview = URL.createObjectURL($event.target.files[0])"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg">
                    </div>
                </div>

                <h4 class="font-bold text-brand-dark">قصة الشركة (3 فقرات)</h4>
                @foreach([1,2,3] as $i)
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">الفقرة {{ $i }}</label>
                    <textarea name="about_story_p{{ $i }}" rows="3"
                        class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">{{ $settings["about_story_p{$i}"] ?? '' }}</textarea>
                </div>
                @endforeach

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ صفحة "من نحن"
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: الرؤية والرسالة ===== --}}
    <div x-show="tab === 'vision'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.vision') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-4">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-eye text-brand-gold"></i> الرؤية والرسالة
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- VISION --}}
                    <div class="space-y-3 p-4 bg-brand-gold/5 rounded-xl border border-brand-gold/20">
                        <h4 class="font-bold text-brand-goldDeep flex items-center gap-2"><i class="fas fa-eye"></i> الرؤية</h4>
                        <div x-data="{ preview: '' }">
                            <label class="block text-sm font-medium text-brand-dark mb-1">صورة الرؤية</label>
                            <div class="w-full h-24 bg-gray-100 rounded-lg overflow-hidden mb-2">
                                <img :src="preview || '{{ $settings['vision_image'] ?? '' }}'" class="w-full h-full object-cover">
                            </div>
                            <input type="file" name="vision_image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])"
                                class="w-full px-3 py-2 border border-brand-border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-1">عنوان الرؤية</label>
                            <input type="text" name="vision_title" value="{{ $settings['vision_title'] ?? '' }}"
                                class="w-full px-3 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-1">نص الرؤية</label>
                            <textarea name="vision_text" rows="5"
                                class="w-full px-3 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none text-sm">{{ $settings['vision_text'] ?? '' }}</textarea>
                        </div>
                    </div>
                    {{-- MISSION --}}
                    <div class="space-y-3 p-4 bg-brand-DEFAULT/5 rounded-xl border border-brand-DEFAULT/20">
                        <h4 class="font-bold text-brand-DEFAULT flex items-center gap-2"><i class="fas fa-bullseye"></i> الرسالة</h4>
                        <div x-data="{ preview: '' }">
                            <label class="block text-sm font-medium text-brand-dark mb-1">صورة الرسالة</label>
                            <div class="w-full h-24 bg-gray-100 rounded-lg overflow-hidden mb-2">
                                <img :src="preview || '{{ $settings['mission_image'] ?? '' }}'" class="w-full h-full object-cover">
                            </div>
                            <input type="file" name="mission_image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])"
                                class="w-full px-3 py-2 border border-brand-border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-1">عنوان الرسالة</label>
                            <input type="text" name="mission_title" value="{{ $settings['mission_title'] ?? '' }}"
                                class="w-full px-3 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-1">نص الرسالة</label>
                            <textarea name="mission_text" rows="5"
                                class="w-full px-3 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none text-sm">{{ $settings['mission_text'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <h4 class="font-bold text-brand-dark">نقاط الرسالة الأربع</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach([1,2,3,4] as $i)
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-1">النقطة {{ $i }}</label>
                        <input type="text" name="mission_bullet{{ $i }}" value="{{ $settings["mission_bullet{$i}"] ?? '' }}"
                            class="w-full px-3 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 text-sm">
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ الرؤية والرسالة
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TAB: صفحة التواصل ===== --}}
    <div x-show="tab === 'contact_page'" x-cloak>
        <form method="POST" action="{{ route('admin.site-settings.contact-page') }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-4">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="fas fa-envelope text-brand-gold"></i> صفحة التواصل
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">عنوان الصفحة</label>
                        <input type="text" name="contact_hero_title" value="{{ $settings['contact_hero_title'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-2">العنوان الفرعي</label>
                        <input type="text" name="contact_hero_subtitle" value="{{ $settings['contact_hero_subtitle'] ?? '' }}"
                            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    </div>
                </div>
                <h4 class="font-bold text-brand-dark">الأسئلة السريعة (3 بطاقات)</h4>
                @foreach([1,2,3] as $i)
                <div class="p-4 bg-gray-50 rounded-xl space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-1">السؤال {{ $i }}</label>
                        <input type="text" name="contact_faq{{ $i }}_q" value="{{ $settings["contact_faq{$i}_q"] ?? '' }}"
                            class="w-full px-3 py-2 border border-brand-border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-dark mb-1">الجواب {{ $i }}</label>
                        <textarea name="contact_faq{{ $i }}_a" rows="2"
                            class="w-full px-3 py-2 border border-brand-border rounded-lg resize-none text-sm">{{ $settings["contact_faq{$i}_a"] ?? '' }}</textarea>
                    </div>
                </div>
                @endforeach
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save"></i> حفظ صفحة التواصل
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
