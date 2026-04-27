<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\AssessmentAttempt;

class AssessmentsController extends Controller
{
    /**
     * Available assessments with their configurations
     */
    private function getAssessments()
    {
        return [
            'holland' => [
                'name' => 'اختبار هولاند للميول المهنية',
                'name_en' => 'Holland RIASEC Test',
                'description' => 'اكتشف ميولك المهنية وفقاً لنظرية RIASEC الشهيرة التي تصنف الشخصيات إلى 6 أنماط رئيسية',
                'duration' => '15-20 دقيقة',
                'questions_count' => 42,
                'dimensions' => ['R', 'I', 'A', 'S', 'E', 'C'],
                'dimension_names' => [
                    'R' => 'الواقعي (Realistic)',
                    'I' => 'الباحث (Investigative)',
                    'A' => 'الفني (Artistic)',
                    'S' => 'الاجتماعي (Social)',
                    'E' => 'المقدام (Enterprising)',
                    'C' => 'التقليدي (Conventional)',
                ],
            ],
            'mbti' => [
                'name' => 'اختبار الشخصية MBTI',
                'name_en' => 'MBTI Personality Test',
                'description' => 'تعرف على نمط شخصيتك من بين 16 نمطاً مختلفاً',
                'duration' => '20-25 دقيقة',
                'questions_count' => 40,
                'dimensions' => ['E/I', 'S/N', 'T/F', 'J/P'],
            ],
            'mi' => [
                'name' => 'اختبار الذكاءات المتعددة',
                'name_en' => 'Multiple Intelligences Test',
                'description' => 'اكتشف أنواع ذكائك المتعددة وكيف تستثمرها',
                'duration' => '15-20 دقيقة',
                'questions_count' => 40,
                'dimensions' => ['linguistic', 'logical', 'spatial', 'musical', 'kinesthetic', 'interpersonal', 'intrapersonal', 'naturalistic'],
            ],
            'work-values' => [
                'name' => 'اختبار القيم المهنية',
                'name_en' => 'Work Values Test',
                'description' => 'تعرف على قيمك المهنية وما يحفزك في العمل',
                'duration' => '10-15 دقيقة',
                'questions_count' => 30,
            ],
            'career-fit' => [
                'name' => 'اختبار الملاءمة المهنية',
                'name_en' => 'Career Fit Test',
                'description' => 'اكتشف المجالات المهنية الأكثر ملاءمة لك',
                'duration' => '15-20 دقيقة',
                'questions_count' => 35,
            ],
        ];
    }

    /**
     * Get Holland test questions
     */
    private function getHollandQuestions()
    {
        return [
            // Realistic (R) - 7 questions
            ['text' => 'أستمتع بإصلاح الأجهزة والمعدات بيدي', 'dimension' => 'R'],
            ['text' => 'أفضل العمل في الهواء الطلق على العمل في مكتب', 'dimension' => 'R'],
            ['text' => 'أحب تشغيل الآلات والمعدات', 'dimension' => 'R'],
            ['text' => 'أجيد استخدام الأدوات اليدوية', 'dimension' => 'R'],
            ['text' => 'أفضل رؤية النتائج الملموسة لعملي', 'dimension' => 'R'],
            ['text' => 'أستمتع ببناء الأشياء', 'dimension' => 'R'],
            ['text' => 'أفضل الأعمال البدنية على الأعمال المكتبية', 'dimension' => 'R'],
            
            // Investigative (I) - 7 questions
            ['text' => 'أستمتع بحل المسائل الرياضية والعلمية', 'dimension' => 'I'],
            ['text' => 'أحب البحث والتقصي لفهم الأمور', 'dimension' => 'I'],
            ['text' => 'أستمتع بتحليل البيانات والمعلومات', 'dimension' => 'I'],
            ['text' => 'أفضل العمل بشكل مستقل لحل المشكلات', 'dimension' => 'I'],
            ['text' => 'أحب قراءة المقالات العلمية والتقنية', 'dimension' => 'I'],
            ['text' => 'أستمتع بإجراء التجارب والاختبارات', 'dimension' => 'I'],
            ['text' => 'أفضل فهم "لماذا" أكثر من "كيف"', 'dimension' => 'I'],
            
            // Artistic (A) - 7 questions
            ['text' => 'أستمتع بالرسم أو التصوير أو التصميم', 'dimension' => 'A'],
            ['text' => 'أحب التعبير عن نفسي بطرق إبداعية', 'dimension' => 'A'],
            ['text' => 'أقدر الفن والجمال', 'dimension' => 'A'],
            ['text' => 'أستمتع بالكتابة الإبداعية أو الشعر', 'dimension' => 'A'],
            ['text' => 'أفضل البيئات غير التقليدية', 'dimension' => 'A'],
            ['text' => 'أحب الموسيقى والغناء أو العزف', 'dimension' => 'A'],
            ['text' => 'أستمتع بتمثيل الأدوار أو المسرح', 'dimension' => 'A'],
            
            // Social (S) - 7 questions
            ['text' => 'أستمتع بمساعدة الآخرين في حل مشاكلهم', 'dimension' => 'S'],
            ['text' => 'أحب العمل مع الناس أكثر من العمل مع الأشياء', 'dimension' => 'S'],
            ['text' => 'أجيد الاستماع للآخرين', 'dimension' => 'S'],
            ['text' => 'أستمتع بتعليم أو تدريب الآخرين', 'dimension' => 'S'],
            ['text' => 'أفضل العمل الجماعي على العمل الفردي', 'dimension' => 'S'],
            ['text' => 'أهتم بصحة ورفاهية الآخرين', 'dimension' => 'S'],
            ['text' => 'أحب التطوع ومساعدة المجتمع', 'dimension' => 'S'],
            
            // Enterprising (E) - 7 questions
            ['text' => 'أستمتع بقيادة الفرق والمشاريع', 'dimension' => 'E'],
            ['text' => 'أحب الإقناع والتأثير على الآخرين', 'dimension' => 'E'],
            ['text' => 'أطمح لتحقيق النجاح المالي', 'dimension' => 'E'],
            ['text' => 'أستمتع بالتفاوض وعقد الصفقات', 'dimension' => 'E'],
            ['text' => 'أفضل اتخاذ القرارات بنفسي', 'dimension' => 'E'],
            ['text' => 'أحب المنافسة والتحديات', 'dimension' => 'E'],
            ['text' => 'أستمتع بإدارة الآخرين وتوجيههم', 'dimension' => 'E'],
            
            // Conventional (C) - 7 questions
            ['text' => 'أستمتع بتنظيم الملفات والبيانات', 'dimension' => 'C'],
            ['text' => 'أفضل العمل ضمن قواعد وإجراءات واضحة', 'dimension' => 'C'],
            ['text' => 'أجيد الاهتمام بالتفاصيل الدقيقة', 'dimension' => 'C'],
            ['text' => 'أحب الدقة والنظام في عملي', 'dimension' => 'C'],
            ['text' => 'أستمتع بالعمل مع الأرقام والجداول', 'dimension' => 'C'],
            ['text' => 'أفضل الوظائف المستقرة والروتينية', 'dimension' => 'C'],
            ['text' => 'أحب التخطيط والجدولة', 'dimension' => 'C'],
        ];
    }

    /**
     * Get MBTI test questions
     */
    private function getMBTIQuestions()
    {
        return [
            // E vs I
            ['text' => 'أشعر بالنشاط عند التواجد مع الآخرين', 'dimension' => 'E', 'opposite' => 'I'],
            ['text' => 'أفضل قضاء الوقت بمفردي للاسترخاء', 'dimension' => 'I', 'opposite' => 'E'],
            ['text' => 'أتحدث بسهولة مع الغرباء', 'dimension' => 'E', 'opposite' => 'I'],
            ['text' => 'أفكر قبل أن أتكلم', 'dimension' => 'I', 'opposite' => 'E'],
            ['text' => 'أفضل العمل في فريق', 'dimension' => 'E', 'opposite' => 'I'],
            ['text' => 'أحتاج وقتاً للتفكير قبل الإجابة', 'dimension' => 'I', 'opposite' => 'E'],
            ['text' => 'أستمتع بالحفلات والتجمعات', 'dimension' => 'E', 'opposite' => 'I'],
            ['text' => 'أفضل المحادثات العميقة على المجاملات', 'dimension' => 'I', 'opposite' => 'E'],
            ['text' => 'لدي دائرة واسعة من المعارف', 'dimension' => 'E', 'opposite' => 'I'],
            ['text' => 'أفضل القراءة والأنشطة الهادئة', 'dimension' => 'I', 'opposite' => 'E'],
            
            // S vs N
            ['text' => 'أركز على الحقائق والتفاصيل', 'dimension' => 'S', 'opposite' => 'N'],
            ['text' => 'أثق بحدسي وإلهامي', 'dimension' => 'N', 'opposite' => 'S'],
            ['text' => 'أفضل الخبرة العملية على النظريات', 'dimension' => 'S', 'opposite' => 'N'],
            ['text' => 'أحب استكشاف الأفكار والإمكانيات الجديدة', 'dimension' => 'N', 'opposite' => 'S'],
            ['text' => 'أفضل الطرق المجربة والموثوقة', 'dimension' => 'S', 'opposite' => 'N'],
            ['text' => 'أستمتع بالتفكير في المستقبل والاحتمالات', 'dimension' => 'N', 'opposite' => 'S'],
            ['text' => 'أنتبه للتفاصيل الصغيرة', 'dimension' => 'S', 'opposite' => 'N'],
            ['text' => 'أرى الصورة الكبيرة قبل التفاصيل', 'dimension' => 'N', 'opposite' => 'S'],
            ['text' => 'أفضل التعليمات الواضحة والمحددة', 'dimension' => 'S', 'opposite' => 'N'],
            ['text' => 'أحب الإبداع والابتكار', 'dimension' => 'N', 'opposite' => 'S'],
            
            // T vs F
            ['text' => 'أتخذ قراراتي بناءً على المنطق', 'dimension' => 'T', 'opposite' => 'F'],
            ['text' => 'أراعي مشاعر الآخرين في قراراتي', 'dimension' => 'F', 'opposite' => 'T'],
            ['text' => 'أفضل الصراحة على المجاملة', 'dimension' => 'T', 'opposite' => 'F'],
            ['text' => 'أسعى للحفاظ على الانسجام مع الآخرين', 'dimension' => 'F', 'opposite' => 'T'],
            ['text' => 'أحلل المشاكل بشكل موضوعي', 'dimension' => 'T', 'opposite' => 'F'],
            ['text' => 'أتعاطف بسهولة مع الآخرين', 'dimension' => 'F', 'opposite' => 'T'],
            ['text' => 'أقدر العدالة والإنصاف', 'dimension' => 'T', 'opposite' => 'F'],
            ['text' => 'أقدر التعاون والتفاهم', 'dimension' => 'F', 'opposite' => 'T'],
            ['text' => 'أنتقد الأفكار بصراحة', 'dimension' => 'T', 'opposite' => 'F'],
            ['text' => 'أشجع الآخرين وأدعمهم', 'dimension' => 'F', 'opposite' => 'T'],
            
            // J vs P
            ['text' => 'أحب التخطيط المسبق', 'dimension' => 'J', 'opposite' => 'P'],
            ['text' => 'أفضل المرونة والتلقائية', 'dimension' => 'P', 'opposite' => 'J'],
            ['text' => 'ألتزم بالمواعيد النهائية', 'dimension' => 'J', 'opposite' => 'P'],
            ['text' => 'أعمل بشكل أفضل تحت الضغط', 'dimension' => 'P', 'opposite' => 'J'],
            ['text' => 'أفضل إنهاء المهام قبل البدء بغيرها', 'dimension' => 'J', 'opposite' => 'P'],
            ['text' => 'أحب ترك الخيارات مفتوحة', 'dimension' => 'P', 'opposite' => 'J'],
            ['text' => 'أحب القوائم والجداول', 'dimension' => 'J', 'opposite' => 'P'],
            ['text' => 'أتكيف بسهولة مع التغييرات', 'dimension' => 'P', 'opposite' => 'J'],
            ['text' => 'أفضل الروتين والثبات', 'dimension' => 'J', 'opposite' => 'P'],
            ['text' => 'أستمتع بالمفاجآت والعفوية', 'dimension' => 'P', 'opposite' => 'J'],
        ];
    }

    /**
     * Get Multiple Intelligences test questions
     */
    private function getMIQuestions()
    {
        return [
            // Linguistic
            ['text' => 'أستمتع بالقراءة والكتابة', 'dimension' => 'linguistic'],
            ['text' => 'أجيد التعبير عن أفكاري بالكلمات', 'dimension' => 'linguistic'],
            ['text' => 'أتعلم بشكل أفضل من خلال الاستماع والقراءة', 'dimension' => 'linguistic'],
            ['text' => 'أحب ألعاب الكلمات والألغاز اللغوية', 'dimension' => 'linguistic'],
            ['text' => 'أتذكر الأشياء بسهولة عند كتابتها', 'dimension' => 'linguistic'],
            
            // Logical-Mathematical
            ['text' => 'أستمتع بحل المسائل الرياضية', 'dimension' => 'logical'],
            ['text' => 'أفكر بطريقة منطقية ومنظمة', 'dimension' => 'logical'],
            ['text' => 'أحب الألغاز والألعاب الاستراتيجية', 'dimension' => 'logical'],
            ['text' => 'أسأل كثيراً عن كيفية عمل الأشياء', 'dimension' => 'logical'],
            ['text' => 'أجيد التعامل مع الأرقام والإحصاءات', 'dimension' => 'logical'],
            
            // Spatial
            ['text' => 'أستمتع بالرسم والتصميم', 'dimension' => 'spatial'],
            ['text' => 'أتذكر الأماكن والخرائط بسهولة', 'dimension' => 'spatial'],
            ['text' => 'أفهم الرسوم البيانية والمخططات بسهولة', 'dimension' => 'spatial'],
            ['text' => 'أحب مشاهدة الأفلام والصور', 'dimension' => 'spatial'],
            ['text' => 'أستطيع تخيل الأشياء في ذهني بوضوح', 'dimension' => 'spatial'],
            
            // Musical
            ['text' => 'أستمتع بالموسيقى والغناء', 'dimension' => 'musical'],
            ['text' => 'أتذكر الألحان بسهولة', 'dimension' => 'musical'],
            ['text' => 'أستطيع تمييز النغمات والإيقاعات', 'dimension' => 'musical'],
            ['text' => 'أدندن أو أغني أثناء العمل', 'dimension' => 'musical'],
            ['text' => 'الموسيقى تؤثر على مزاجي بشكل كبير', 'dimension' => 'musical'],
            
            // Kinesthetic
            ['text' => 'أتعلم بشكل أفضل من خلال التجربة العملية', 'dimension' => 'kinesthetic'],
            ['text' => 'أستمتع بالرياضة والأنشطة البدنية', 'dimension' => 'kinesthetic'],
            ['text' => 'أجد صعوبة في الجلوس لفترات طويلة', 'dimension' => 'kinesthetic'],
            ['text' => 'أجيد الحرف اليدوية والأعمال الفنية', 'dimension' => 'kinesthetic'],
            ['text' => 'أستخدم يدي كثيراً عند الحديث', 'dimension' => 'kinesthetic'],
            
            // Interpersonal
            ['text' => 'أفهم مشاعر الآخرين بسهولة', 'dimension' => 'interpersonal'],
            ['text' => 'أستمتع بالعمل مع الآخرين', 'dimension' => 'interpersonal'],
            ['text' => 'لدي القدرة على حل النزاعات', 'dimension' => 'interpersonal'],
            ['text' => 'أحب مساعدة الآخرين', 'dimension' => 'interpersonal'],
            ['text' => 'أجيد التواصل مع الناس', 'dimension' => 'interpersonal'],
            
            // Intrapersonal
            ['text' => 'أفهم مشاعري وأفكاري جيداً', 'dimension' => 'intrapersonal'],
            ['text' => 'أحتاج وقتاً للتفكير بمفردي', 'dimension' => 'intrapersonal'],
            ['text' => 'أعرف نقاط قوتي وضعفي', 'dimension' => 'intrapersonal'],
            ['text' => 'لدي أهداف واضحة في الحياة', 'dimension' => 'intrapersonal'],
            ['text' => 'أستمتع بالتأمل والتفكير العميق', 'dimension' => 'intrapersonal'],
            
            // Naturalistic
            ['text' => 'أستمتع بالطبيعة والبيئة', 'dimension' => 'naturalistic'],
            ['text' => 'أهتم بالحيوانات والنباتات', 'dimension' => 'naturalistic'],
            ['text' => 'ألاحظ التفاصيل في البيئة من حولي', 'dimension' => 'naturalistic'],
            ['text' => 'أحب التصنيف والترتيب', 'dimension' => 'naturalistic'],
            ['text' => 'أفضل قضاء الوقت في الطبيعة', 'dimension' => 'naturalistic'],
        ];
    }

    /**
     * Display list of all assessments
     */
    public function index()
    {
        $assessments = $this->getAssessments();
        return view('assessments.index', compact('assessments'));
    }

    /**
     * Display the assessment form
     */
    public function show($slug)
    {
        $assessments = $this->getAssessments();
        
        if (!isset($assessments[$slug])) {
            abort(404);
        }

        $assessment = $assessments[$slug];
        $assessment['slug'] = $slug;
        
        // Get questions based on assessment type
        switch ($slug) {
            case 'holland':
                $questions = $this->getHollandQuestions();
                break;
            case 'mbti':
                $questions = $this->getMBTIQuestions();
                break;
            case 'mi':
                $questions = $this->getMIQuestions();
                break;
            default:
                $questions = $this->getHollandQuestions(); // Default to Holland
        }

        return view('assessments.show', compact('assessment', 'questions'));
    }

    /**
     * Process assessment submission
     */
    public function submit(Request $request, $slug)
    {
        $assessments = $this->getAssessments();
        
        if (!isset($assessments[$slug])) {
            abort(404);
        }

        $answers = $request->input('answers', []);
        
        // Calculate scores based on assessment type
        switch ($slug) {
            case 'holland':
                $result = $this->calculateHollandResult($answers);
                break;
            case 'mbti':
                $result = $this->calculateMBTIResult($answers);
                break;
            case 'mi':
                $result = $this->calculateMIResult($answers);
                break;
            default:
                $result = $this->calculateHollandResult($answers);
        }

        // Save to database
        $attempt = AssessmentAttempt::create([
            'user_id' => Auth::id(),
            'guest_name' => $request->input('guest_name'),
            'guest_email' => $request->input('guest_email'),
            'guest_phone' => $request->input('guest_phone'),
            'assessment_slug' => $slug,
            'assessment_name' => $assessments[$slug]['name'],
            'answers_json' => $answers,
            'scores_json' => $result['scores'],
            'type_code' => $result['type_code'],
            'summary' => $result['interpretation']['summary'] ?? null,
            'status' => 'completed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Store result in session with attempt ID
        Session::put('assessment_result', [
            'attempt_id' => $attempt->id,
            'slug' => $slug,
            'assessment' => $assessments[$slug],
            'scores' => $result['scores'],
            'type_code' => $result['type_code'],
            'interpretation' => $result['interpretation'],
            'timestamp' => now(),
        ]);

        return redirect()->route('assessments.result', $slug);
    }

    /**
     * Calculate Holland RIASEC result
     */
    private function calculateHollandResult($answers)
    {
        $questions = $this->getHollandQuestions();
        $scores = ['R' => 0, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0];
        
        foreach ($answers as $index => $value) {
            if (isset($questions[$index])) {
                $dimension = $questions[$index]['dimension'];
                $scores[$dimension] += (int)$value;
            }
        }

        // Get top 3 dimensions for the code
        arsort($scores);
        $topThree = array_slice(array_keys($scores), 0, 3);
        $typeCode = implode('', $topThree);

        $interpretation = $this->getHollandInterpretation($typeCode, $scores);

        return [
            'scores' => $scores,
            'type_code' => $typeCode,
            'interpretation' => $interpretation,
        ];
    }

    /**
     * Calculate MBTI result
     */
    private function calculateMBTIResult($answers)
    {
        $questions = $this->getMBTIQuestions();
        $scores = ['E' => 0, 'I' => 0, 'S' => 0, 'N' => 0, 'T' => 0, 'F' => 0, 'J' => 0, 'P' => 0];
        
        foreach ($answers as $index => $value) {
            if (isset($questions[$index])) {
                $dimension = $questions[$index]['dimension'];
                $scores[$dimension] += (int)$value;
            }
        }

        // Determine type
        $typeCode = '';
        $typeCode .= $scores['E'] >= $scores['I'] ? 'E' : 'I';
        $typeCode .= $scores['S'] >= $scores['N'] ? 'S' : 'N';
        $typeCode .= $scores['T'] >= $scores['F'] ? 'T' : 'F';
        $typeCode .= $scores['J'] >= $scores['P'] ? 'J' : 'P';

        $interpretation = $this->getMBTIInterpretation($typeCode);

        return [
            'scores' => $scores,
            'type_code' => $typeCode,
            'interpretation' => $interpretation,
        ];
    }

    /**
     * Calculate Multiple Intelligences result
     */
    private function calculateMIResult($answers)
    {
        $questions = $this->getMIQuestions();
        $scores = [
            'linguistic' => 0,
            'logical' => 0,
            'spatial' => 0,
            'musical' => 0,
            'kinesthetic' => 0,
            'interpersonal' => 0,
            'intrapersonal' => 0,
            'naturalistic' => 0,
        ];
        
        foreach ($answers as $index => $value) {
            if (isset($questions[$index])) {
                $dimension = $questions[$index]['dimension'];
                $scores[$dimension] += (int)$value;
            }
        }

        arsort($scores);
        $topThree = array_slice(array_keys($scores), 0, 3);

        $interpretation = $this->getMIInterpretation($topThree, $scores);

        return [
            'scores' => $scores,
            'type_code' => implode('-', $topThree),
            'interpretation' => $interpretation,
        ];
    }

    /**
     * Get Holland interpretation
     */
    private function getHollandInterpretation($typeCode, $scores)
    {
        $dimensionDescriptions = [
            'R' => [
                'name' => 'الواقعي (Realistic)',
                'description' => 'تميل إلى العمل مع الأشياء والآلات، وتفضل الأنشطة البدنية والعملية.',
                'careers' => ['مهندس', 'فني', 'ميكانيكي', 'طيار', 'مزارع', 'كهربائي', 'نجار'],
            ],
            'I' => [
                'name' => 'الباحث (Investigative)',
                'description' => 'تحب التفكير والتحليل والبحث، وتستمتع بحل المشكلات العلمية.',
                'careers' => ['عالم', 'باحث', 'طبيب', 'محلل بيانات', 'مهندس برمجيات', 'صيدلي'],
            ],
            'A' => [
                'name' => 'الفني (Artistic)',
                'description' => 'تميل إلى الإبداع والتعبير عن النفس، وتقدر الجمال والفن.',
                'careers' => ['مصمم', 'فنان', 'كاتب', 'مخرج', 'موسيقي', 'مصور', 'معماري'],
            ],
            'S' => [
                'name' => 'الاجتماعي (Social)',
                'description' => 'تحب العمل مع الناس ومساعدتهم، ولديك مهارات تواصل قوية.',
                'careers' => ['معلم', 'مرشد', 'أخصائي اجتماعي', 'ممرض', 'موارد بشرية', 'مدرب'],
            ],
            'E' => [
                'name' => 'المقدام (Enterprising)',
                'description' => 'تحب القيادة والتأثير، ولديك طموح للنجاح والإنجاز.',
                'careers' => ['مدير', 'رائد أعمال', 'مسوق', 'محامي', 'سياسي', 'مندوب مبيعات'],
            ],
            'C' => [
                'name' => 'التقليدي (Conventional)',
                'description' => 'تفضل النظام والتنظيم، وتجيد العمل مع البيانات والتفاصيل.',
                'careers' => ['محاسب', 'إداري', 'سكرتير', 'مدقق', 'أمين مكتبة', 'موظف بنك'],
            ],
        ];

        $topType = substr($typeCode, 0, 1);
        $mainDescription = $dimensionDescriptions[$topType];

        return [
            'main_type' => $mainDescription,
            'dimension_descriptions' => $dimensionDescriptions,
            'summary' => "كود هولاند الخاص بك هو: {$typeCode}. النمط السائد لديك هو {$mainDescription['name']}.",
        ];
    }

    /**
     * Get MBTI interpretation
     */
    private function getMBTIInterpretation($typeCode)
    {
        $types = [
            'INTJ' => ['name' => 'المهندس المعماري', 'description' => 'مفكر استراتيجي، مبدع، ومستقل. يحب التخطيط طويل المدى.'],
            'INTP' => ['name' => 'المنطقي', 'description' => 'محب للمعرفة، تحليلي، ومبتكر. يستمتع بحل المشكلات المعقدة.'],
            'ENTJ' => ['name' => 'القائد', 'description' => 'قائد طبيعي، حازم، وطموح. يجيد اتخاذ القرارات.'],
            'ENTP' => ['name' => 'المناظر', 'description' => 'مبدع، ذكي، ويحب التحدي. يستمتع بالنقاشات الفكرية.'],
            'INFJ' => ['name' => 'المدافع', 'description' => 'مثالي، متعاطف، ومبدع. يسعى لمساعدة الآخرين.'],
            'INFP' => ['name' => 'الوسيط', 'description' => 'مثالي، حساس، ومبدع. يقدر القيم والمعتقدات.'],
            'ENFJ' => ['name' => 'البطل', 'description' => 'قائد ملهم، متعاطف، وكاريزمي. يحب مساعدة الآخرين على النمو.'],
            'ENFP' => ['name' => 'المناصر', 'description' => 'متحمس، مبدع، واجتماعي. يحب الاحتمالات الجديدة.'],
            'ISTJ' => ['name' => 'اللوجستي', 'description' => 'عملي، موثوق، ومنظم. يقدر التقاليد والمسؤولية.'],
            'ISFJ' => ['name' => 'المدافع', 'description' => 'متعاطف، موثوق، ومتفان. يهتم برفاهية الآخرين.'],
            'ESTJ' => ['name' => 'المدير التنفيذي', 'description' => 'منظم، عملي، وحازم. يجيد إدارة المشاريع.'],
            'ESFJ' => ['name' => 'القنصل', 'description' => 'اجتماعي، متعاون، ومهتم. يحب رعاية الآخرين.'],
            'ISTP' => ['name' => 'الحرفي', 'description' => 'عملي، مرن، ومحلل. يجيد حل المشكلات التقنية.'],
            'ISFP' => ['name' => 'المغامر', 'description' => 'فني، حساس، ومرن. يقدر الجمال والتناغم.'],
            'ESTP' => ['name' => 'رائد الأعمال', 'description' => 'نشيط، عملي، ومغامر. يحب الإثارة والتحديات.'],
            'ESFP' => ['name' => 'المؤدي', 'description' => 'عفوي، نشيط، وممتع. يحب الترفيه ومشاركة الآخرين.'],
        ];

        $typeInfo = $types[$typeCode] ?? ['name' => $typeCode, 'description' => 'نمط شخصيتك فريد.'];

        return [
            'type_info' => $typeInfo,
            'summary' => "نمط شخصيتك هو: {$typeCode} - {$typeInfo['name']}",
        ];
    }

    /**
     * Get Multiple Intelligences interpretation
     */
    private function getMIInterpretation($topThree, $scores)
    {
        $intelligences = [
            'linguistic' => ['name' => 'الذكاء اللغوي', 'description' => 'قدرة متميزة على استخدام الكلمات والتعبير اللغوي.', 'careers' => ['كاتب', 'محامي', 'صحفي', 'معلم لغات']],
            'logical' => ['name' => 'الذكاء المنطقي-الرياضي', 'description' => 'قدرة على التفكير المنطقي وحل المسائل الرياضية.', 'careers' => ['مهندس', 'عالم', 'محاسب', 'مبرمج']],
            'spatial' => ['name' => 'الذكاء المكاني', 'description' => 'قدرة على التفكير بالصور والتصور المكاني.', 'careers' => ['مهندس معماري', 'فنان', 'طيار', 'مصمم']],
            'musical' => ['name' => 'الذكاء الموسيقي', 'description' => 'حساسية للإيقاعات والألحان والأصوات.', 'careers' => ['موسيقي', 'مغني', 'منتج موسيقي', 'معلم موسيقى']],
            'kinesthetic' => ['name' => 'الذكاء الجسدي-الحركي', 'description' => 'قدرة على استخدام الجسم بمهارة.', 'careers' => ['رياضي', 'راقص', 'جراح', 'حرفي']],
            'interpersonal' => ['name' => 'الذكاء الاجتماعي', 'description' => 'قدرة على فهم الآخرين والتواصل معهم.', 'careers' => ['مرشد', 'مدير', 'معالج نفسي', 'مسوق']],
            'intrapersonal' => ['name' => 'الذكاء الذاتي', 'description' => 'قدرة على فهم الذات والمشاعر الداخلية.', 'careers' => ['كاتب', 'فيلسوف', 'مستشار', 'رائد أعمال']],
            'naturalistic' => ['name' => 'الذكاء الطبيعي', 'description' => 'قدرة على فهم الطبيعة والكائنات الحية.', 'careers' => ['عالم أحياء', 'مزارع', 'طبيب بيطري', 'عالم بيئة']],
        ];

        $topIntelligences = [];
        foreach ($topThree as $key) {
            $topIntelligences[] = $intelligences[$key];
        }

        return [
            'top_intelligences' => $topIntelligences,
            'all_intelligences' => $intelligences,
            'summary' => 'ذكاءاتك الأقوى هي: ' . implode('، ', array_column($topIntelligences, 'name')),
        ];
    }

    /**
     * Display assessment result
     */
    public function result($slug)
    {
        $result = Session::get('assessment_result');
        
        if (!$result || $result['slug'] !== $slug) {
            return redirect()->route('assessments.show', $slug)
                ->with('error', 'يرجى إكمال الاختبار أولاً');
        }

        return view('assessments.result', [
            'result' => $result,
            'slug' => $slug,
        ]);
    }
}
