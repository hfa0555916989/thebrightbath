@extends('layouts.admin')

@section('title', 'نماذج التحليل الوظيفي والكفاءات')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-brand-dark">نماذج التحليل الوظيفي والكفاءات</h1>
            <p class="text-brand-textMuted mt-1">إدارة النماذج التفاعلية من ملفات Excel</p>
        </div>
        <a href="{{ route('admin.analysis-models.create') }}" 
           class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg">
            <i class="fas fa-plus"></i>
            إضافة نموذج جديد
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-table text-brand-gold text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $models->count() }}</div>
                    <div class="text-gray-500 text-sm">إجمالي النماذج</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $models->where('is_active', true)->count() }}</div>
                    <div class="text-gray-500 text-sm">نماذج مفعّلة</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye text-blue-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($models->sum('views_count')) }}</div>
                    <div class="text-gray-500 text-sm">إجمالي المشاهدات</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-download text-purple-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($models->sum('downloads_count')) }}</div>
                    <div class="text-gray-500 text-sm">إجمالي التحميلات</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Models List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-list text-brand-gold"></i>
                قائمة النماذج
            </h2>
        </div>
        
        @if($models->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">النموذج</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">الملف</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">الإحصائيات</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">تاريخ الإنشاء</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($models as $model)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $model->color }}20;">
                                    <i class="fas {{ $model->icon }} text-xl" style="color: {{ $model->color }};"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $model->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($model->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.analysis-models.toggle-active', $model) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium transition
                                    {{ $model->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    <i class="fas {{ $model->is_active ? 'fa-check' : 'fa-times' }}"></i>
                                    {{ $model->is_active ? 'مفعّل' : 'معطّل' }}
                                </button>
                            </form>
                            @if($model->is_featured)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-brand-gold/20 text-brand-goldDeep mr-1">
                                <i class="fas fa-star"></i> مميز
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-file-excel text-green-600 ml-1"></i>
                                {{ $model->original_file_name ?? 'لا يوجد ملف' }}
                            </div>
                            @if($model->data)
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $model->data['row_count'] ?? 0 }} صف
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span title="المشاهدات">
                                    <i class="fas fa-eye text-blue-500 ml-1"></i>
                                    {{ number_format($model->views_count) }}
                                </span>
                                <span title="التحميلات">
                                    <i class="fas fa-download text-purple-500 ml-1"></i>
                                    {{ number_format($model->downloads_count) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $model->created_at->format('Y/m/d') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('analysis-models.show', $model) }}" target="_blank"
                                   class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition"
                                   title="معاينة">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="{{ route('admin.analysis-models.edit', $model) }}"
                                   class="w-9 h-9 rounded-lg bg-brand-gold/20 text-brand-goldDeep flex items-center justify-center hover:bg-brand-gold/30 transition"
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.analysis-models.destroy', $model) }}" method="POST" class="inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا النموذج؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-9 h-9 rounded-lg bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-table text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد نماذج بعد</h3>
            <p class="text-gray-500 mb-6">ابدأ بإضافة نموذج جديد من ملف Excel</p>
            <a href="{{ route('admin.analysis-models.create') }}" 
               class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                <i class="fas fa-plus"></i>
                إضافة نموذج جديد
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
