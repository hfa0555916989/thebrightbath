@extends('layouts.admin')

@section('title', 'إدارة المستشارين')
@section('page-title', 'إدارة المستشارين')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <p class="text-gray-600">إدارة المستشارين وجدولة مواعيدهم</p>
        <a href="{{ route('admin.consultants.create') }}" 
           class="bg-brand-gold text-brand-dark px-6 py-3 rounded-xl font-medium hover:bg-brand-goldDeep transition flex items-center gap-2">
            <i class="fas fa-plus"></i>
            إضافة مستشار جديد
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-brand-gold/10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-xl text-brand-gold"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-brand-dark">{{ $consultants->total() }}</p>
                    <p class="text-sm text-gray-500">إجمالي</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check text-xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-brand-dark">{{ $consultants->where('is_active', true)->count() }}</p>
                    <p class="text-sm text-gray-500">نشط</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-star text-xl text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-brand-dark">{{ $consultants->where('is_featured', true)->count() }}</p>
                    <p class="text-sm text-gray-500">مميز</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-brand-dark">{{ $consultants->sum('total_sessions') }}</p>
                    <p class="text-sm text-gray-500">جلسة</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">المستشار</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">التخصص</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">السعر</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">التقييم</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($consultants as $consultant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($consultant->photo)
                                    <img src="{{ asset('storage/' . $consultant->photo) }}" alt="{{ $consultant->user->name }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-brand-gold/20 flex items-center justify-center">
                                        <i class="fas fa-user text-brand-gold"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-brand-dark">{{ $consultant->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $consultant->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $consultant->specialization_ar }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ number_format($consultant->price_per_30_min) }} ر.س</p>
                            <p class="text-sm text-gray-500">30 دقيقة</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="font-semibold">{{ $consultant->rating }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($consultant->is_active)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">نشط</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">معطل</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.consultants.schedule', $consultant) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="المواعيد">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                                <a href="{{ route('admin.consultants.edit', $consultant) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.consultants.destroy', $consultant) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-user-tie text-4xl mb-4"></i>
                            <p>لا يوجد مستشارين حالياً</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($consultants->hasPages())
        <div class="px-6 py-4 border-t">{{ $consultants->links() }}</div>
        @endif
    </div>
</div>
@endsection




