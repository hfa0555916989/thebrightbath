<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ===== VISUAL =====
            ['group' => 'visual', 'key' => 'color_primary',   'value' => '#1F3A63', 'type' => 'color',  'label' => 'اللون الأساسي (الأزرق الداكن)',  'description' => 'اللون الرئيسي للموقع'],
            ['group' => 'visual', 'key' => 'color_gold',      'value' => '#F8C524', 'type' => 'color',  'label' => 'اللون الذهبي',                   'description' => 'لون التأكيد الذهبي'],
            ['group' => 'visual', 'key' => 'color_gold_deep', 'value' => '#E5A91F', 'type' => 'color',  'label' => 'اللون الذهبي الداكن (hover)',    'description' => 'يُستخدم عند تحريك الماوس'],
            ['group' => 'visual', 'key' => 'color_gold_light','value' => '#FFD96A', 'type' => 'color',  'label' => 'اللون الذهبي الفاتح',            'description' => ''],
            ['group' => 'visual', 'key' => 'color_orange',    'value' => '#F28C28', 'type' => 'color',  'label' => 'اللون البرتقالي',                'description' => ''],
            ['group' => 'visual', 'key' => 'color_dark',      'value' => '#162032', 'type' => 'color',  'label' => 'اللون الداكن (خلفية)',           'description' => 'لون الـ navbar والـ footer'],
            ['group' => 'visual', 'key' => 'color_bg',        'value' => '#F5F7FA', 'type' => 'color',  'label' => 'لون الخلفية العامة',             'description' => ''],
            ['group' => 'visual', 'key' => 'color_text',      'value' => '#1F2933', 'type' => 'color',  'label' => 'لون النص الرئيسي',              'description' => ''],
            ['group' => 'visual', 'key' => 'color_text_muted','value' => '#6B7280', 'type' => 'color',  'label' => 'لون النص الثانوي',              'description' => ''],
            ['group' => 'visual', 'key' => 'color_border',    'value' => '#E0E6ED', 'type' => 'color',  'label' => 'لون الحدود',                    'description' => ''],
            ['group' => 'visual', 'key' => 'font_primary',    'value' => 'Tajawal', 'type' => 'text',   'label' => 'الخط الرئيسي',                  'description' => 'خط Google Fonts'],
            ['group' => 'visual', 'key' => 'font_display',    'value' => 'Noto Kufi Arabic', 'type' => 'text', 'label' => 'خط العناوين',            'description' => 'خط Google Fonts'],

            // ===== CONTACT =====
            ['group' => 'contact', 'key' => 'phone',             'value' => '+966 54 349 4316',  'type' => 'text',  'label' => 'رقم الهاتف',              'description' => 'يظهر في الفوتر وصفحة التواصل'],
            ['group' => 'contact', 'key' => 'whatsapp',          'value' => '966543494316',      'type' => 'text',  'label' => 'رقم الواتساب',            'description' => 'بدون + أو مسافات'],
            ['group' => 'contact', 'key' => 'whatsapp_message',  'value' => 'السلام عليكم، أرغب في الاستفسار عن خدماتكم', 'type' => 'text', 'label' => 'رسالة الواتساب الافتراضية', 'description' => ''],
            ['group' => 'contact', 'key' => 'email',             'value' => 'cs@thebrightbath.com', 'type' => 'text', 'label' => 'البريد الإلكتروني',   'description' => ''],
            ['group' => 'contact', 'key' => 'address',           'value' => 'المملكة العربية السعودية', 'type' => 'text', 'label' => 'العنوان',          'description' => ''],
            ['group' => 'contact', 'key' => 'working_hours',     'value' => 'الأحد - الخميس: 9 ص - 5 م', 'type' => 'text', 'label' => 'ساعات العمل',  'description' => ''],

            // ===== SOCIAL =====
            ['group' => 'social', 'key' => 'social_twitter',   'value' => '#', 'type' => 'url', 'label' => 'رابط تويتر / X',   'description' => ''],
            ['group' => 'social', 'key' => 'social_instagram', 'value' => '#', 'type' => 'url', 'label' => 'رابط إنستقرام',     'description' => ''],
            ['group' => 'social', 'key' => 'social_linkedin',  'value' => '#', 'type' => 'url', 'label' => 'رابط لينكدإن',      'description' => ''],

            // ===== SEO =====
            ['group' => 'seo', 'key' => 'site_name',     'value' => 'الطريق المشرق للتدريب والتطوير', 'type' => 'text',     'label' => 'اسم الموقع',          'description' => 'يظهر في تبويب المتصفح وبطاقات المشاركة'],
            ['group' => 'seo', 'key' => 'site_tagline',  'value' => 'رواد في التدريب والتطوير المهني والإرشاد الوظيفي', 'type' => 'text', 'label' => 'الشعار النصي', 'description' => ''],
            ['group' => 'seo', 'key' => 'ga_id',         'value' => 'G-K7ZEBV8Z8D', 'type' => 'text', 'label' => 'معرّف Google Analytics', 'description' => 'GA4 Measurement ID'],
            ['group' => 'seo', 'key' => 'og_description','value' => 'اختبارات الميول المهنية والإرشاد الوظيفي مع نخبة من المستشارين المتخصصين', 'type' => 'textarea', 'label' => 'وصف الموقع العام (OG)', 'description' => 'يظهر عند مشاركة رابط الموقع'],

            // ===== FOOTER =====
            ['group' => 'footer', 'key' => 'footer_description',   'value' => 'الطريق المشرق للتدريب والتطوير - رواد في التدريب والتطوير المهني والإرشاد الوظيفي.', 'type' => 'textarea', 'label' => 'وصف الفوتر',          'description' => ''],
            ['group' => 'footer', 'key' => 'copyright_text',        'value' => 'الطريق المشرق للتدريب والتطوير. جميع الحقوق محفوظة.',                                 'type' => 'text',     'label' => 'نص حقوق النشر',        'description' => ''],
            ['group' => 'footer', 'key' => 'newsletter_title',      'value' => 'انضم إلى مجتمع الطريق المشرق',                                                         'type' => 'text',     'label' => 'عنوان النشرة البريدية', 'description' => ''],
            ['group' => 'footer', 'key' => 'newsletter_subtitle',   'value' => 'اشترك ليصلك كل جديد من مقالات ونصائح مهنية وعروض حصرية',                              'type' => 'text',     'label' => 'وصف النشرة البريدية',  'description' => ''],

            // ===== HERO (Home) =====
            ['group' => 'hero', 'key' => 'hero_badge',         'value' => 'الطريق المشرق للتدريب والتطوير',            'type' => 'text',     'label' => 'نص الشارة (Badge)',         'description' => 'الشريط الصغير فوق العنوان'],
            ['group' => 'hero', 'key' => 'hero_title_1',       'value' => 'اكتشف ميولك المهنية',                       'type' => 'text',     'label' => 'العنوان الرئيسي - السطر الأول', 'description' => ''],
            ['group' => 'hero', 'key' => 'hero_gold_word',     'value' => 'ميولك المهنية',                              'type' => 'text',     'label' => 'الكلمة الذهبية في العنوان',  'description' => 'يجب أن تكون جزءاً من السطر الأول'],
            ['group' => 'hero', 'key' => 'hero_title_2',       'value' => 'وابنِ مستقبلك بثقة',                        'type' => 'text',     'label' => 'العنوان الرئيسي - السطر الثاني', 'description' => ''],
            ['group' => 'hero', 'key' => 'hero_cta_primary',   'value' => 'ابدأ الاختبار الآن',                        'type' => 'text',     'label' => 'نص الزر الرئيسي',           'description' => ''],
            ['group' => 'hero', 'key' => 'hero_cta_secondary', 'value' => 'سجل مجاناً',                                'type' => 'text',     'label' => 'نص الزر الثانوي',           'description' => ''],
            ['group' => 'hero', 'key' => 'hero_image',         'value' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', 'type' => 'image', 'label' => 'صورة خلفية Hero', 'description' => 'يمكن رفع صورة أو إدخال رابط URL'],

            // ===== ABOUT PAGE =====
            ['group' => 'about', 'key' => 'about_hero_title',    'value' => 'عن الطريق المشرق',                                           'type' => 'text',     'label' => 'عنوان صفحة "من نحن"',      'description' => ''],
            ['group' => 'about', 'key' => 'about_hero_subtitle', 'value' => 'رحلة التميز في التدريب والتطوير المهني والإرشاد الوظيفي',     'type' => 'text',     'label' => 'وصف صفحة "من نحن"',       'description' => ''],
            ['group' => 'about', 'key' => 'about_hero_image',    'value' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', 'type' => 'image', 'label' => 'صورة خلفية "من نحن"', 'description' => ''],
            ['group' => 'about', 'key' => 'about_story_image',   'value' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',  'type' => 'image', 'label' => 'صورة قسم القصة',          'description' => ''],
            ['group' => 'about', 'key' => 'about_story_p1',      'value' => 'تأسست الطريق المشرق للتدريب والتطوير بهدف تقديم خدمات إرشاد مهني احترافية تساعد الأفراد على اكتشاف مساراتهم المهنية المناسبة.', 'type' => 'textarea', 'label' => 'قصة الشركة - الفقرة الأولى', 'description' => ''],
            ['group' => 'about', 'key' => 'about_story_p2',      'value' => 'نعمل مع نخبة من المستشارين والمدربين المعتمدين الذين يمتلكون خبرة واسعة في مجالات التطوير المهني والإرشاد الوظيفي.', 'type' => 'textarea', 'label' => 'قصة الشركة - الفقرة الثانية', 'description' => ''],
            ['group' => 'about', 'key' => 'about_story_p3',      'value' => 'نؤمن بأن كل شخص يمتلك مواهب وقدرات فريدة تنتظر الاكتشاف والتطوير، ومهمتنا مساعدتك في رحلة التعرف على نفسك وبناء مستقبل مهني مشرق.', 'type' => 'textarea', 'label' => 'قصة الشركة - الفقرة الثالثة', 'description' => ''],

            // ===== VISION & MISSION =====
            ['group' => 'vision', 'key' => 'vision_title',   'value' => 'نحو مستقبل مهني مشرق للجميع', 'type' => 'text',     'label' => 'عنوان الرؤية',   'description' => ''],
            ['group' => 'vision', 'key' => 'vision_text',    'value' => 'أن تكون الطريق المشرق مرجعًا مميزًا في التدريب والتطوير في مجال الموارد البشرية والإرشاد المهني، ومزوّدًا رائدًا للحلول التدريبية والاعتمادات الدولية التي تسهم في صناعة كفاءات مهنية قادرة على قيادة المستقبل.', 'type' => 'textarea', 'label' => 'نص الرؤية الكاملة', 'description' => ''],
            ['group' => 'vision', 'key' => 'vision_image',   'value' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'type' => 'image', 'label' => 'صورة الرؤية', 'description' => ''],
            ['group' => 'vision', 'key' => 'mission_title',  'value' => 'تمكين الأفراد والمؤسسات', 'type' => 'text',     'label' => 'عنوان الرسالة',  'description' => ''],
            ['group' => 'vision', 'key' => 'mission_text',   'value' => 'نسعى لتمكين الأفراد والمؤسسات من خلال تقديم برامج تدريبية متخصصة، وجلسات إرشاد مهني احترافية، وتأهيل مهني معتمد دوليًا، وذلك عبر منهجيات تعليمية حديثة، ومدربين مؤهلين، وحلول تدريبية مبتكرة تعزز التطور المهني وتلائم متطلبات سوق العمل المتجددة.', 'type' => 'textarea', 'label' => 'نص الرسالة الكاملة', 'description' => ''],
            ['group' => 'vision', 'key' => 'mission_image',  'value' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'type' => 'image', 'label' => 'صورة الرسالة', 'description' => ''],
            ['group' => 'vision', 'key' => 'mission_bullet1','value' => 'توفير اختبارات علمية معتمدة ودقيقة', 'type' => 'text', 'label' => 'نقطة الرسالة 1', 'description' => ''],
            ['group' => 'vision', 'key' => 'mission_bullet2','value' => 'تقديم جلسات إرشادية فردية متخصصة',  'type' => 'text', 'label' => 'نقطة الرسالة 2', 'description' => ''],
            ['group' => 'vision', 'key' => 'mission_bullet3','value' => 'تطوير برامج تدريبية مبتكرة',         'type' => 'text', 'label' => 'نقطة الرسالة 3', 'description' => ''],
            ['group' => 'vision', 'key' => 'mission_bullet4','value' => 'دعم المؤسسات في تطوير موظفيها',      'type' => 'text', 'label' => 'نقطة الرسالة 4', 'description' => ''],

            // ===== CONTACT PAGE =====
            ['group' => 'contact_page', 'key' => 'contact_hero_title',    'value' => 'تواصل معنا',                                    'type' => 'text', 'label' => 'عنوان صفحة التواصل',   'description' => ''],
            ['group' => 'contact_page', 'key' => 'contact_hero_subtitle', 'value' => 'نسعد بتلقي استفساراتكم والرد عليها في أقرب وقت ممكن', 'type' => 'text', 'label' => 'وصف صفحة التواصل', 'description' => ''],
            ['group' => 'contact_page', 'key' => 'contact_faq1_q', 'value' => 'كيف أبدأ الاختبار؟', 'type' => 'text', 'label' => 'سؤال سريع 1', 'description' => ''],
            ['group' => 'contact_page', 'key' => 'contact_faq1_a', 'value' => 'اختر الاختبار المناسب من صفحة الاختبارات وابدأ مباشرة - بدون تسجيل!', 'type' => 'textarea', 'label' => 'جواب سريع 1', 'description' => ''],
            ['group' => 'contact_page', 'key' => 'contact_faq2_q', 'value' => 'كم يستغرق الرد؟', 'type' => 'text', 'label' => 'سؤال سريع 2', 'description' => ''],
            ['group' => 'contact_page', 'key' => 'contact_faq2_a', 'value' => 'نرد على جميع الاستفسارات خلال 24-48 ساعة عمل', 'type' => 'textarea', 'label' => 'جواب سريع 2', 'description' => ''],
            ['group' => 'contact_page', 'key' => 'contact_faq3_q', 'value' => 'كيف أحجز جلسة؟', 'type' => 'text', 'label' => 'سؤال سريع 3', 'description' => ''],
            ['group' => 'contact_page', 'key' => 'contact_faq3_a', 'value' => 'أرسل لنا طلبك وسنتواصل معك لتحديد الموعد المناسب', 'type' => 'textarea', 'label' => 'جواب سريع 3', 'description' => ''],

            // ===== HOME - CTA SECTION =====
            ['group' => 'home', 'key' => 'home_cta_title',    'value' => 'هل أنت مستعد لاكتشاف ميولك المهنية؟', 'type' => 'text',  'label' => 'عنوان قسم CTA (الرئيسية)',    'description' => ''],
            ['group' => 'home', 'key' => 'home_cta_image',    'value' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', 'type' => 'image', 'label' => 'صورة قسم CTA', 'description' => ''],
            ['group' => 'home', 'key' => 'home_about_title',  'value' => 'من نحن',                               'type' => 'text',  'label' => 'عنوان قسم "من نحن" بالرئيسية', 'description' => ''],
            ['group' => 'home', 'key' => 'home_about_body',   'value' => 'الطريق المشرق للتدريب والتطوير منصة متخصصة في الإرشاد المهني واختبارات الميول، نساعدك على اكتشاف شخصيتك المهنية وبناء مسارك الوظيفي بثقة.', 'type' => 'textarea', 'label' => 'نص "من نحن" بالرئيسية', 'description' => ''],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
