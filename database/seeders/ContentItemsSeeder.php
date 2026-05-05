<?php

namespace Database\Seeders;

use App\Models\ContentItem;
use Illuminate\Database\Seeder;

class ContentItemsSeeder extends Seeder
{
    public function run(): void
    {
        ContentItem::truncate();

        $items = [
            // ===== STATS (Home) =====
            ['type'=>'stat','page'=>'home','title'=>'+5000','subtitle'=>'مستفيد من خدماتنا','order'=>1,'icon'=>'fas fa-users','color'=>'#F8C524'],
            ['type'=>'stat','page'=>'home','title'=>'5',    'subtitle'=>'اختبارات متخصصة',  'order'=>2,'icon'=>'fas fa-clipboard-list','color'=>'#1F3A63'],
            ['type'=>'stat','page'=>'home','title'=>'+10',  'subtitle'=>'سنوات من الخبرة',   'order'=>3,'icon'=>'fas fa-award','color'=>'#F28C28'],
            ['type'=>'stat','page'=>'home','title'=>'98%',  'subtitle'=>'نسبة رضا العملاء',  'order'=>4,'icon'=>'fas fa-star','color'=>'#22c55e'],

            // ===== FEATURES (Home - شريط الميزات) =====
            ['type'=>'feature','page'=>'home','title'=>'اختبارات معتمدة','body'=>'مبنية على نظريات علمية معتمدة عالمياً','order'=>1,'icon'=>'fas fa-certificate','color'=>'#F8C524'],
            ['type'=>'feature','page'=>'home','title'=>'سرية تامة',      'body'=>'بياناتك محمية بالكامل ولن تُشارك مع أحد','order'=>2,'icon'=>'fas fa-user-shield','color'=>'#1F3A63'],
            ['type'=>'feature','page'=>'home','title'=>'نتائج فورية',    'body'=>'تحصل على تقريرك المفصل فور إكمال الاختبار','order'=>3,'icon'=>'fas fa-bolt','color'=>'#F28C28'],
            ['type'=>'feature','page'=>'home','title'=>'دعم متواصل',    'body'=>'فريقنا متاح للإجابة على استفساراتك','order'=>4,'icon'=>'fas fa-headset','color'=>'#22c55e'],

            // ===== SERVICES (Home) =====
            ['type'=>'service','page'=>'home','title'=>'اختبارات الميول المهنية','body'=>'اكتشف ميولك المهنية من خلال اختبارات علمية معتمدة مثل هولاند و MBTI والذكاءات المتعددة','order'=>1,'icon'=>'fas fa-clipboard-list','color'=>'#1F3A63','image'=>'https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80','link'=>'assessments.index'],
            ['type'=>'service','page'=>'home','title'=>'الإرشاد المهني الفردي',  'body'=>'جلسات إرشادية فردية مع مستشارين متخصصين لمساعدتك في وضع خطة مهنية واضحة','order'=>2,'icon'=>'fas fa-user-tie',      'color'=>'#F8C524','image'=>'https://images.unsplash.com/photo-1551836022-d5d88e9218df?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80','link'=>'consultations.index'],
            ['type'=>'service','page'=>'home','title'=>'برامج التدريب والتطوير', 'body'=>'برامج تدريبية متخصصة في مهارات التواصل والقيادة وإدارة الوقت والمقابلات الوظيفية','order'=>3,'icon'=>'fas fa-chalkboard-teacher','color'=>'#F28C28','image'=>'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80','link'=>'contact'],

            // ===== STEPS - كيف يعمل (Home) =====
            ['type'=>'step','page'=>'home','title'=>'اختر الاختبار', 'body'=>'اختر من بين مجموعة متنوعة من الاختبارات المعتمدة','order'=>1,'icon'=>'fas fa-hand-pointer','color'=>'#F8C524'],
            ['type'=>'step','page'=>'home','title'=>'أجب على الأسئلة','body'=>'أجب بصدق وتلقائية للحصول على نتائج دقيقة',      'order'=>2,'icon'=>'fas fa-edit',         'color'=>'#1F3A63'],
            ['type'=>'step','page'=>'home','title'=>'احصل على النتائج','body'=>'استلم تقريرك المفصل فوراً بعد إكمال الاختبار', 'order'=>3,'icon'=>'fas fa-chart-pie',    'color'=>'#F28C28'],
            ['type'=>'step','page'=>'home','title'=>'ابدأ رحلتك',     'body'=>'اتخذ قراراتك المهنية بثقة انطلاقاً من نتائجك',  'order'=>4,'icon'=>'fas fa-rocket',      'color'=>'#22c55e'],

            // ===== TESTIMONIALS (Home) =====
            ['type'=>'testimonial','page'=>'home','title'=>'محمد العتيبي','subtitle'=>'مهندس - الرياض','body'=>'اختبار هولاند ساعدني كثيراً في فهم ميولي المهنية واتخاذ قرار تغيير مساري الوظيفي. الآن أعمل في مجال يناسبني تماماً.','order'=>1,'color'=>'#1F3A63','meta'=>['rating'=>5,'avatar_letter'=>'م']],
            ['type'=>'testimonial','page'=>'home','title'=>'نورة الشمري',  'subtitle'=>'خريجة - جدة',   'body'=>'الكتاب المهني كان مرجعاً قيماً لي في رحلة البحث عن عمل. المعلومات مفيدة جداً والتقارير واضحة ومفصلة.',                  'order'=>2,'color'=>'#F8C524','meta'=>['rating'=>5,'avatar_letter'=>'ن']],
            ['type'=>'testimonial','page'=>'home','title'=>'عبدالرحمن القحطاني','subtitle'=>'طالب جامعي - الدمام','body'=>'جلسة الإرشاد المهني كانت نقطة تحول في حياتي. المرشد كان محترفاً وساعدني على وضع خطة واضحة لمستقبلي.','order'=>3,'color'=>'#F28C28','meta'=>['rating'=>4.5,'avatar_letter'=>'ع']],

            // ===== SERVICES PAGE - خدمات تفصيلية =====
            ['type'=>'service_detail','page'=>'services','title'=>'اختبارات الميول المهنية','subtitle'=>'الخدمة الأولى','body'=>'نوفر مجموعة من الاختبارات العلمية المعتمدة لمساعدتك على اكتشاف ميولك المهنية وتحديد المسار الوظيفي المناسب.','order'=>1,'icon'=>'fas fa-clipboard-list','color'=>'#3b82f6','meta'=>['badge_color'=>'blue','items'=>['اختبار هولاند للميول المهنية (RIASEC)','اختبار أنماط الشخصية (MBTI)','اختبار الذكاءات المتعددة','اختبار القيم المهنية'],'btn_text'=>'استعرض الاختبارات','btn_color'=>'bg-brand-DEFAULT']],
            ['type'=>'service_detail','page'=>'services','title'=>'الإرشاد المهني الفردي',  'subtitle'=>'الخدمة الثانية','body'=>'جلسات إرشادية فردية مع مستشارين متخصصين لتحليل نتائجك وبناء خطة مهنية واضحة تلائم قدراتك وطموحاتك.','order'=>2,'icon'=>'fas fa-user-tie',       'color'=>'#f59e0b','meta'=>['badge_color'=>'amber','items'=>['تحليل معمق لنتائج الاختبارات','وضع خطة مهنية واضحة','توجيه في اختيار التخصص أو الوظيفة','متابعة ودعم مستمر'],'btn_text'=>'احجز جلستك الآن','btn_color'=>'bg-brand-gold']],
            ['type'=>'service_detail','page'=>'services','title'=>'برامج التدريب والتطوير', 'subtitle'=>'الخدمة الثالثة','body'=>'برامج تدريبية متخصصة تغطي أهم المهارات المطلوبة في سوق العمل الحديث، مقدمة من مدربين معتمدين ذوي خبرة.','order'=>3,'icon'=>'fas fa-chalkboard-teacher','color'=>'#22c55e','meta'=>['badge_color'=>'green','items'=>['مهارات التواصل والعرض','القيادة وإدارة الفرق','إدارة الوقت والضغوط','مهارات المقابلات الوظيفية'],'btn_text'=>'استفسر عن البرامج','btn_color'=>'bg-green-600']],
            ['type'=>'service_detail','page'=>'services','title'=>'استشارات المؤسسات',      'subtitle'=>'الخدمة الرابعة','body'=>'خدمات متكاملة للمؤسسات والشركات تشمل تقييم الكفاءات وبناء خطط التطوير المهني وتصميم برامج تدريبية مخصصة.','order'=>4,'icon'=>'fas fa-building',      'color'=>'#a855f7','meta'=>['badge_color'=>'purple','items'=>['تقييم الكفاءات والمهارات','بناء خطط التطوير المهني','استشارات التوظيف والاختيار','برامج تدريبية مخصصة'],'btn_text'=>'تواصل معنا','btn_color'=>'bg-purple-600']],

            // ===== VALUES =====
            ['type'=>'value','page'=>'global','title'=>'التميز',              'body'=>'نسعى دائماً لتقديم أفضل ما لدينا في كل خدمة نقدمها. نلتزم بأعلى معايير الجودة في اختباراتنا وبرامجنا التدريبية وجلساتنا الإرشادية.','order'=>1,'icon'=>'fas fa-medal',       'color'=>'#F8C524'],
            ['type'=>'value','page'=>'global','title'=>'الأمانة والمصداقية', 'body'=>'نتعامل بشفافية ومصداقية مع جميع عملائنا. نقدم معلومات دقيقة وصادقة، ونحافظ على سرية بيانات المستفيدين.','order'=>2,'icon'=>'fas fa-handshake',   'color'=>'#1F3A63'],
            ['type'=>'value','page'=>'global','title'=>'الإبداع والتطوير',   'body'=>'نؤمن بأهمية التطوير المستمر والبحث عن حلول مبتكرة. نحرص على تحديث أدواتنا ومناهجنا لمواكبة أحدث الممارسات العالمية.','order'=>3,'icon'=>'fas fa-lightbulb',  'color'=>'#F28C28'],
            ['type'=>'value','page'=>'global','title'=>'الشراكة والتعاون',   'body'=>'نبني علاقات شراكة حقيقية مع عملائنا. نرافقهم في رحلتهم المهنية ونعمل معاً لتحقيق أهدافهم.','order'=>4,'icon'=>'fas fa-users',       'color'=>'#22c55e'],
            ['type'=>'value','page'=>'global','title'=>'الاحترام والتقدير',  'body'=>'نحترم تفرد كل فرد ونقدر اختلافاته. نتعامل مع الجميع باحترام ونقدر ثقتهم بنا في مسيرتهم المهنية.','order'=>5,'icon'=>'fas fa-heart',       'color'=>'#a855f7'],
            ['type'=>'value','page'=>'global','title'=>'الموثوقية العلمية',  'body'=>'نعتمد على أسس علمية راسخة في جميع خدماتنا. اختباراتنا مبنية على نظريات مثبتة ومعتمدة عالمياً.','order'=>6,'icon'=>'fas fa-shield-alt', 'color'=>'#3b82f6'],

            // ===== STRATEGIC GOALS =====
            ['type'=>'goal','page'=>'global','title'=>'التوسع في تقديم الخدمات',    'body'=>'توسيع نطاق خدماتنا لتشمل المزيد من الاختبارات والبرامج التدريبية المتخصصة، والوصول إلى شريحة أكبر من المستفيدين في جميع أنحاء المملكة.','order'=>1,'color'=>'#F8C524','meta'=>['sub_goals'=>['إضافة 10 اختبارات جديدة خلال 3 سنوات','الوصول إلى 50,000 مستفيد سنوياً']]],
            ['type'=>'goal','page'=>'global','title'=>'تعزيز الشراكات الاستراتيجية','body'=>'بناء شراكات قوية مع الجامعات والمؤسسات التعليمية والشركات لتوفير خدمات الإرشاد المهني للطلاب والموظفين.','order'=>2,'color'=>'#1F3A63','meta'=>['sub_goals'=>['شراكات مع 20 جامعة وجهة تعليمية','تعاون مع 50 شركة في القطاع الخاص']]],
            ['type'=>'goal','page'=>'global','title'=>'التميز في جودة الخدمات',     'body'=>'الحصول على اعتمادات وشهادات جودة محلية ودولية، وتطوير فريق العمل بشكل مستمر لضمان أعلى مستويات الجودة.','order'=>3,'color'=>'#F28C28','meta'=>['sub_goals'=>['الحصول على شهادة ISO في جودة الخدمات','تحقيق نسبة رضا عملاء 95%']]],
            ['type'=>'goal','page'=>'global','title'=>'الريادة في التقنية والابتكار','body'=>'تطوير منصة رقمية متكاملة تقدم تجربة مستخدم متميزة، واستخدام أحدث التقنيات في تحليل البيانات والذكاء الاصطناعي.','order'=>4,'color'=>'#22c55e','meta'=>['sub_goals'=>['إطلاق تطبيق موبايل متكامل','دمج تقنيات الذكاء الاصطناعي في التحليل']]],
            ['type'=>'goal','page'=>'global','title'=>'المسؤولية المجتمعية',         'body'=>'تقديم خدمات مجانية للفئات الأقل حظاً، والمساهمة في رفع الوعي بأهمية الإرشاد المهني في المجتمع.','order'=>5,'color'=>'#a855f7','meta'=>['sub_goals'=>['تقديم 1000 اختبار مجاني سنوياً','إقامة 20 فعالية توعوية سنوياً']]],

            // ===== TEAM (About page) =====
            ['type'=>'team','page'=>'about','title'=>'مستشارو الإرشاد المهني','body'=>'متخصصون في توجيه الأفراد نحو المسار المهني الأمثل','order'=>1,'image'=>'https://images.pexels.com/photos/5669619/pexels-photo-5669619.jpeg?auto=compress&cs=tinysrgb&w=400'],
            ['type'=>'team','page'=>'about','title'=>'المدربون المعتمدون',    'body'=>'خبراء في تقديم البرامج التدريبية المتخصصة',           'order'=>2,'image'=>'https://images.pexels.com/photos/7504837/pexels-photo-7504837.jpeg?auto=compress&cs=tinysrgb&w=400'],
            ['type'=>'team','page'=>'about','title'=>'خبراء القياس النفسي',  'body'=>'متخصصون في تطبيق وتحليل الاختبارات النفسية',         'order'=>3,'image'=>'https://images.unsplash.com/photo-1551836022-d5d88e9218df?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],

            // ===== PROCESS STEPS (Consultations page) =====
            ['type'=>'process_step','page'=>'consultations','title'=>'اختر المستشار','body'=>'اختر المستشار المناسب حسب تخصصك','order'=>1,'color'=>'#F8C524'],
            ['type'=>'process_step','page'=>'consultations','title'=>'حدد الموعد',   'body'=>'اختر الوقت المناسب من الجدول',      'order'=>2,'color'=>'#F8C524'],
            ['type'=>'process_step','page'=>'consultations','title'=>'أكمل الدفع',   'body'=>'ادفع بشكل آمن عبر البطاقة',        'order'=>3,'color'=>'#F8C524'],
            ['type'=>'process_step','page'=>'consultations','title'=>'انضم للجلسة', 'body'=>'استلم رابط الاجتماع عبر البريد',   'order'=>4,'color'=>'#F8C524'],

            // ===== ABOUT PAGE VALUES (preview في صفحة "من نحن") =====
            ['type'=>'about_value','page'=>'about','title'=>'التميز',  'body'=>'نسعى دائماً لتقديم أفضل ما لدينا','order'=>1,'icon'=>'fas fa-award',     'color'=>'#F8C524','image'=>'https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80'],
            ['type'=>'about_value','page'=>'about','title'=>'الأمانة', 'body'=>'نتعامل بشفافية ومصداقية',          'order'=>2,'icon'=>'fas fa-handshake','color'=>'#1F3A63','image'=>'https://images.unsplash.com/photo-1521737711867-e3b97375f902?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80'],
            ['type'=>'about_value','page'=>'about','title'=>'الإبداع', 'body'=>'نبتكر حلولاً متميزة',              'order'=>3,'icon'=>'fas fa-lightbulb','color'=>'#F28C28','image'=>'https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80'],
            ['type'=>'about_value','page'=>'about','title'=>'الشراكة', 'body'=>'نبني علاقات حقيقية مع عملائنا',    'order'=>4,'icon'=>'fas fa-users',    'color'=>'#22c55e','image'=>'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80'],
        ];

        foreach ($items as $item) {
            ContentItem::create($item);
        }
    }
}
