@extends('layouts.admin')

@section('title', 'إضافة اختبار جديد')
@section('header', 'إضافة اختبار جديد')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.assessments.index') }}" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right text-gray-600"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">إضافة اختبار جديد</h2>
            <p class="text-brand-textMuted mt-1">إنشاء اختبار ميول مهنية جديد</p>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-yellow-800 mb-2">ملاحظة مهمة</h4>
                <p class="text-yellow-700 text-sm">
                    إضافة اختبارات جديدة يتطلب معرفة تقنية متقدمة. الاختبارات الحالية (هولاند، MBTI، الذكاءات المتعددة، القيم المهنية، التوافق المهني) 
                    مبنية على نظريات علمية ومُعدة بعناية. إذا كنت ترغب في إضافة اختبار جديد، يرجى التواصل مع فريق التطوير.
                </p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">معلومات الاختبار</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">اسم الاختبار <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required disabled
                                   class="w-full px-4 py-3 border border-brand-border rounded-lg bg-gray-100 cursor-not-allowed"
                                   placeholder="مثال: اختبار الميول الفنية">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الوصف المختصر</label>
                            <textarea name="description" rows="3" disabled
                                      class="w-full px-4 py-3 border border-brand-border rounded-lg bg-gray-100 cursor-not-allowed resize-none"
                                      placeholder="وصف مختصر للاختبار..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">نوع الاختبار</label>
                            <select name="type" disabled class="w-full px-4 py-3 border border-brand-border rounded-lg bg-gray-100 cursor-not-allowed">
                                <option value="">اختر النوع</option>
                                <option value="interests">ميول مهنية</option>
                                <option value="personality">تحليل شخصية</option>
                                <option value="intelligence">ذكاءات</option>
                                <option value="values">قيم مهنية</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">الإعدادات</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الحالة</label>
                            <select name="status" disabled class="w-full px-4 py-2 border border-brand-border rounded-lg bg-gray-100 cursor-not-allowed">
                                <option value="draft">مسودة</option>
                                <option value="active">نشط</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الوقت المحدد (دقائق)</label>
                            <input type="number" name="time_limit" value="30" min="5" disabled
                                   class="w-full px-4 py-2 border border-brand-border rounded-lg bg-gray-100 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-3">
                    <button type="button" disabled class="w-full bg-gray-300 text-gray-500 py-3 rounded-lg font-bold cursor-not-allowed">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الاختبار
                    </button>
                    
                    <a href="{{ route('admin.assessments.index') }}" class="block text-center py-3 border border-brand-border rounded-lg text-brand-textMuted hover:bg-gray-50 transition">
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

