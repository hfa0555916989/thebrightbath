@extends('layouts.admin')

@section('title', 'فصول الكتاب')
@section('header', 'فصول الكتاب المهني')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">فصول الكتاب المهني</h2>
            <p class="text-brand-textMuted mt-1">إدارة فصول ومحتوى الكتاب</p>
        </div>
        <a href="{{ route('admin.book-chapters.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-gold text-brand-dark rounded-lg hover:bg-brand-goldDeep transition font-medium">
            <i class="fas fa-plus"></i>
            <span>إضافة فصل جديد</span>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">إجمالي الفصول</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">{{ $chapters->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-brand-gold text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">فصول منشورة</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $chapters->where('is_published', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">فصول مسودة</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $chapters->where('is_published', false)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-edit text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Chapters List --}}
    <div class="bg-white rounded-xl shadow-sm border border-brand-border overflow-hidden">
        <div class="p-6 border-b border-brand-border">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-bold text-brand-dark">قائمة الفصول</h3>
                <input type="text" placeholder="بحث في الفصول..." 
                       class="px-4 py-2 border border-brand-border rounded-lg text-sm focus:ring-2 focus:ring-brand-gold/20 w-full sm:w-64">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">العنوان</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الوصف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">المشاهدات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border">
                    @forelse($chapters as $chapter)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-brand-textMuted">{{ $chapter->order }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-brand-dark">{{ $chapter->title }}</div>
                            @if($chapter->is_free)
                                <span class="text-xs text-green-600 font-medium">مجاني</span>
                            @else
                                <span class="text-xs text-brand-textMuted">مدفوع</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-brand-textMuted max-w-xs truncate">
                            {{ $chapter->excerpt ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($chapter->is_published)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <i class="fas fa-circle text-[6px]"></i> منشور
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    <i class="fas fa-circle text-[6px]"></i> مسودة
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-brand-textMuted">—</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.book-chapters.edit', $chapter) }}"
                                   class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.book-chapters.destroy', $chapter) }}" method="POST"
                                      onsubmit="return confirm('هل تريد حذف هذا الفصل؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-brand-textMuted">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">لا توجد فصول حالياً</p>
                                <p class="text-sm mb-4">ابدأ بإضافة فصول الكتاب المهني</p>
                                <a href="{{ route('admin.book-chapters.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-gold text-brand-dark rounded-lg hover:bg-brand-goldDeep transition">
                                    <i class="fas fa-plus"></i>
                                    <span>إضافة فصل جديد</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

