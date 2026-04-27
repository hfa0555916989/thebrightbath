@extends('layouts.admin')

@section('title', 'إدارة الاختبارات')
@section('header', 'إدارة الاختبارات')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">إدارة الاختبارات</h2>
            <p class="text-brand-textMuted mt-1">إدارة اختبارات الميول المهنية</p>
        </div>
        <a href="{{ route('admin.assessments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-gold text-brand-dark rounded-lg hover:bg-brand-goldDeep transition font-medium">
            <i class="fas fa-plus"></i>
            <span>إضافة اختبار</span>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">إجمالي الاختبارات</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">5</p>
                </div>
                <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-brand-gold text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">اختبارات نشطة</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">5</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">إجمالي الأسئلة</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">200+</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">محاولات هذا الشهر</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">--</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Assessments List --}}
    <div class="bg-white rounded-xl shadow-sm border border-brand-border overflow-hidden">
        <div class="p-6 border-b border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark">قائمة الاختبارات</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الاختبار</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الأسئلة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">المحاولات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border">
                    {{-- Holland Test --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-compass text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-brand-dark">اختبار هولاند (RIASEC)</p>
                                    <p class="text-sm text-brand-textMuted">اكتشف ميولك المهنية</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-brand-textMuted">ميول مهنية</td>
                        <td class="px-6 py-4 text-sm text-brand-dark font-medium">42 سؤال</td>
                        <td class="px-6 py-4 text-sm text-brand-dark">--</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">نشط</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('assessments.show', 'holland') }}" target="_blank" class="p-2 text-gray-400 hover:text-brand-gold transition" title="معاينة">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- MBTI Test --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-brain text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-brand-dark">اختبار MBTI</p>
                                    <p class="text-sm text-brand-textMuted">تحليل نمط الشخصية</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-brand-textMuted">تحليل شخصية</td>
                        <td class="px-6 py-4 text-sm text-brand-dark font-medium">70 سؤال</td>
                        <td class="px-6 py-4 text-sm text-brand-dark">--</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">نشط</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('assessments.show', 'mbti') }}" target="_blank" class="p-2 text-gray-400 hover:text-brand-gold transition" title="معاينة">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- MI Test --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-brand-dark">اختبار الذكاءات المتعددة</p>
                                    <p class="text-sm text-brand-textMuted">اكتشف أنواع ذكاءاتك</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-brand-textMuted">ذكاءات</td>
                        <td class="px-6 py-4 text-sm text-brand-dark font-medium">56 سؤال</td>
                        <td class="px-6 py-4 text-sm text-brand-dark">--</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">نشط</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('assessments.show', 'multiple-intelligences') }}" target="_blank" class="p-2 text-gray-400 hover:text-brand-gold transition" title="معاينة">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Work Values Test --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-star text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-brand-dark">اختبار القيم المهنية</p>
                                    <p class="text-sm text-brand-textMuted">اكتشف قيمك في العمل</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-brand-textMuted">قيم مهنية</td>
                        <td class="px-6 py-4 text-sm text-brand-dark font-medium">35 سؤال</td>
                        <td class="px-6 py-4 text-sm text-brand-dark">--</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">نشط</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('assessments.show', 'work-values') }}" target="_blank" class="p-2 text-gray-400 hover:text-brand-gold transition" title="معاينة">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Career Fit Test --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-bullseye text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-brand-dark">اختبار التوافق المهني</p>
                                    <p class="text-sm text-brand-textMuted">اكتشف المهن المناسبة</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-brand-textMuted">توافق مهني</td>
                        <td class="px-6 py-4 text-sm text-brand-dark font-medium">40 سؤال</td>
                        <td class="px-6 py-4 text-sm text-brand-dark">--</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">نشط</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('assessments.show', 'career-fit') }}" target="_blank" class="p-2 text-gray-400 hover:text-brand-gold transition" title="معاينة">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-blue-800 mb-2">معلومات</h4>
                <p class="text-blue-700 text-sm">
                    الاختبارات المعروضة هي اختبارات مُعدة مسبقاً ومبنية على نظريات علمية معتمدة في الإرشاد المهني.
                    يمكنك مراجعة نتائج المستخدمين من صفحة <a href="{{ route('admin.attempts.index') }}" class="underline font-medium">نتائج الاختبارات</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

