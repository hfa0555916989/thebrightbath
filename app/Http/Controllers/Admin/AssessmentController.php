<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    /**
     * Display list of assessments
     */
    public function index()
    {
        $assessments = Assessment::withCount(['questions', 'attempts'])->get();
        
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.assessments.create');
    }

    /**
     * Store new assessment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:assessments,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'estimated_minutes' => 'integer|min:5|max:120',
            'icon' => 'nullable|string|max:50',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Default config
        $validated['config_json'] = [
            'scale' => [
                1 => 'لا أوافق بشدة',
                2 => 'لا أوافق',
                3 => 'محايد',
                4 => 'أوافق',
                5 => 'أوافق بشدة',
            ],
            'dimensions' => [],
            'interpretations' => [],
        ];

        Assessment::create($validated);

        return redirect()->route('admin.assessments.index')
            ->with('success', 'تم إنشاء الاختبار بنجاح');
    }

    /**
     * Show edit form
     */
    public function edit(Assessment $assessment)
    {
        return view('admin.assessments.edit', compact('assessment'));
    }

    /**
     * Update assessment
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:assessments,slug,' . $assessment->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'estimated_minutes' => 'integer|min:5|max:120',
            'icon' => 'nullable|string|max:50',
            'config_json' => 'nullable|array',
        ]);

        $assessment->update($validated);

        return redirect()->route('admin.assessments.index')
            ->with('success', 'تم تحديث الاختبار بنجاح');
    }

    /**
     * Show questions management
     */
    public function questions(Assessment $assessment)
    {
        $questions = $assessment->questions()->orderBy('order')->get();
        
        return view('admin.assessments.questions', compact('assessment', 'questions'));
    }

    /**
     * Store questions (bulk)
     */
    public function storeQuestions(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.code' => 'required|string|max:20',
            'questions.*.text_ar' => 'required|string',
            'questions.*.dimension_key' => 'required|string|max:20',
            'questions.*.order' => 'integer|min:0',
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            AssessmentQuestion::updateOrCreate(
                [
                    'assessment_id' => $assessment->id,
                    'code' => $questionData['code'],
                ],
                [
                    'text_ar' => $questionData['text_ar'],
                    'dimension_key' => $questionData['dimension_key'],
                    'order' => $questionData['order'] ?? $index,
                ]
            );
        }

        return redirect()->route('admin.assessments.questions', $assessment)
            ->with('success', 'تم حفظ الأسئلة بنجاح');
    }

    /**
     * Delete a question
     */
    public function deleteQuestion(Assessment $assessment, AssessmentQuestion $question)
    {
        if ($question->assessment_id !== $assessment->id) {
            abort(404);
        }

        $question->delete();

        return redirect()->route('admin.assessments.questions', $assessment)
            ->with('success', 'تم حذف السؤال');
    }
}







