@extends('layouts.admin')

@section('title', 'نتائج الاختبارات')

@section('content')
<div class="space-y-6">
    
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">نتائج الاختبارات</h1>
            <p class="text-gray-600 mt-1">جميع نتائج اختبارات الميول المهنية</p>
        </div>
        <a href="{{ route('admin.attempts.export', request()->query()) }}" 
           class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-file-excel"></i>
            <span>تصدير Excel</span>
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="text-3xl font-bold text-brand-DEFAULT">{{ $stats['total'] }}</div>
            <div class="text-gray-500 text-sm">إجمالي النتائج</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="text-3xl font-bold text-green-600">{{ $stats['today'] }}</div>
            <div class="text-gray-500 text-sm">اليوم</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['this_week'] }}</div>
            <div class="text-gray-500 text-sm">هذا الأسبوع</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="text-3xl font-bold text-purple-600">{{ $stats['this_month'] }}</div>
            <div class="text-gray-500 text-sm">هذا الشهر</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['new'] }}</div>
            <div class="text-gray-500 text-sm">جديد</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="text-3xl font-bold text-gray-600">{{ $stats['reviewed'] }}</div>
            <div class="text-gray-500 text-sm">تمت المراجعة</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <form action="{{ route('admin.attempts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="اسم، بريد، نتيجة..."
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">نوع الاختبار</label>
                <select name="assessment" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
                    <option value="">الكل</option>
                    @foreach($assessmentTypes as $slug => $name)
                        <option value="{{ $slug }}" {{ request('assessment') == $slug ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
                    <option value="">الكل</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>جديد</option>
                    <option value="viewed" {{ request('status') == 'viewed' ? 'selected' : '' }}>تم الاطلاع</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>تمت المراجعة</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-brand-DEFAULT text-white px-4 py-2 rounded-lg hover:bg-brand-dark transition">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.attempts.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Results Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">#</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">العميل</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">الاختبار</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">النتيجة</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">التاريخ</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attempts as $attempt)
                    <tr class="hover:bg-gray-50 transition {{ $attempt->status === 'completed' ? 'bg-yellow-50' : '' }}">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $attempt->id }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-DEFAULT/10 flex items-center justify-center">
                                    @if($attempt->user)
                                        <i class="fas fa-user text-brand-DEFAULT"></i>
                                    @else
                                        <i class="fas fa-user-clock text-gray-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $attempt->client_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $attempt->client_email ?? 'بدون بريد' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm text-gray-700">{{ $attempt->assessment_name }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-brand-gold/20 text-brand-dark">
                                {{ $attempt->type_code }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($attempt->status === 'completed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                                    <i class="fas fa-circle text-[6px] mr-1"></i> جديد
                                </span>
                            @elseif($attempt->status === 'viewed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                    <i class="fas fa-eye text-[10px] mr-1"></i> تم الاطلاع
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                    <i class="fas fa-check text-[10px] mr-1"></i> تمت المراجعة
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-700">{{ $attempt->created_at->format('Y/m/d') }}</div>
                            <div class="text-xs text-gray-500">{{ $attempt->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.attempts.show', $attempt->id) }}" 
                                   class="p-2 text-brand-DEFAULT hover:bg-brand-DEFAULT/10 rounded-lg transition"
                                   title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($attempt->client_phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $attempt->client_phone) }}" 
                                   target="_blank"
                                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                   title="واتساب">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                @endif
                                <form action="{{ route('admin.attempts.destroy', $attempt->id) }}" method="POST" 
                                      onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                            <p>لا توجد نتائج</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($attempts->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $attempts->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


