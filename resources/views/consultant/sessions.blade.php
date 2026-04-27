@extends('layouts.consultant')

@section('title', 'جميع الجلسات')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">📋 جميع الجلسات</h1>
            <p class="text-gray-500 mt-1">قائمة بجميع جلساتك الاستشارية</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">العميل</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">التاريخ</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">الوقت</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">الحالة</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center ml-3">
                                            <span class="text-primary-600 font-bold text-sm">{{ mb_substr($session->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $session->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $session->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-600">{{ Carbon\Carbon::parse($session->booking_date)->format('Y/m/d') }}</td>
                                <td class="py-4 px-6 text-gray-600">{{ Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>
                                <td class="py-4 px-6">
                                    @switch($session->status)
                                        @case('confirmed')
                                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700">مؤكد</span>
                                            @break
                                        @case('pending')
                                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700">قيد الانتظار</span>
                                            @break
                                        @case('completed')
                                            <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-700">مكتمل</span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-700">ملغي</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        @if($session->status === 'confirmed')
                                        <a href="{{ route('video-call.join', $session) }}" class="bg-brand-gold text-brand-dark px-3 py-1 rounded-lg text-sm font-medium hover:bg-brand-goldDeep transition">
                                            <i class="fas fa-video ml-1"></i>
                                            انضم
                                        </a>
                                        @endif
                                        <a href="{{ route('consultant.client-details', $session) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                            التفاصيل
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-500">
                                    <span class="text-4xl block mb-4">📭</span>
                                    لا توجد جلسات حتى الآن
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($sessions->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



