@extends('layouts.consultant')

@section('title', 'تفاصيل العميل')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('consultant.sessions') }}" class="text-primary-600 hover:text-primary-700 text-sm mb-4 inline-block">
                ← العودة للجلسات
            </a>
            <h1 class="text-2xl font-bold text-gray-900">👤 تفاصيل العميل</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Client Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 bg-gradient-to-l from-primary-600 to-primary-800 text-white text-center">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl font-bold">{{ mb_substr($booking->user->name, 0, 1) }}</span>
                        </div>
                        <h2 class="text-xl font-bold">{{ $booking->user->name }}</h2>
                        <p class="text-primary-100 text-sm">{{ $booking->user->email }}</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">📅 تاريخ الجلسة</span>
                                <span class="font-semibold">{{ Carbon\Carbon::parse($booking->booking_date)->format('Y/m/d') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">⏰ الوقت</span>
                                <span class="font-semibold">{{ Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">📞 الحالة</span>
                                @switch($booking->status)
                                    @case('confirmed')
                                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700">مؤكد</span>
                                        @break
                                    @case('completed')
                                        <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-700">مكتمل</span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        @if($booking->client_notes)
                            <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                                <h3 class="font-semibold text-gray-700 mb-2">📝 ملاحظات العميل</h3>
                                <p class="text-gray-600 text-sm">{{ $booking->client_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assessment Results -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">📊 نتائج اختبارات العميل</h2>
                        <p class="text-gray-500 text-sm mt-1">اطلع على نتائج الاختبارات للتحضير للجلسة</p>
                    </div>
                    <div class="p-6">
                        @if($assessmentResults->isEmpty())
                            <div class="text-center py-12">
                                <span class="text-4xl block mb-4">📋</span>
                                <p class="text-gray-500">لم يُكمل العميل أي اختبارات بعد</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($assessmentResults as $result)
                                    <div class="p-4 bg-gray-50 rounded-xl">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="font-semibold text-gray-900">
                                                @switch($result->assessment_slug)
                                                    @case('holland')
                                                        🎯 اختبار هولاند (RIASEC)
                                                        @break
                                                    @case('mbti')
                                                        🧠 اختبار MBTI
                                                        @break
                                                    @case('mi')
                                                        💡 اختبار الذكاءات المتعددة
                                                        @break
                                                    @default
                                                        📝 {{ $result->assessment_slug }}
                                                @endswitch
                                            </h3>
                                            <span class="text-sm text-gray-500">{{ $result->created_at->format('Y/m/d') }}</span>
                                        </div>
                                        
                                        @php
                                            $resultData = is_string($result->result) ? json_decode($result->result, true) : $result->result;
                                        @endphp
                                        
                                        @if($result->assessment_slug === 'holland' && isset($resultData['scores']))
                                            <div class="grid grid-cols-6 gap-2 mb-3">
                                                @foreach($resultData['scores'] as $type => $score)
                                                    <div class="text-center">
                                                        <div class="text-lg font-bold text-primary-600">{{ $score }}</div>
                                                        <div class="text-xs text-gray-500">{{ $type }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if(isset($resultData['primaryType']))
                                                <p class="text-sm">
                                                    <span class="font-semibold">النوع الرئيسي:</span> 
                                                    <span class="text-primary-600">{{ $resultData['primaryType'] }}</span>
                                                </p>
                                            @endif
                                        @elseif($result->assessment_slug === 'mbti' && isset($resultData['type']))
                                            <p class="text-lg font-bold text-primary-600">{{ $resultData['type'] }}</p>
                                        @elseif($result->assessment_slug === 'mi' && isset($resultData['scores']))
                                            <div class="space-y-2">
                                                @foreach(array_slice($resultData['scores'], 0, 3, true) as $intelligence => $score)
                                                    <div class="flex items-center">
                                                        <div class="flex-1">
                                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                                <div class="h-full bg-primary-500 rounded-full" style="width: {{ min($score * 20, 100) }}%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="w-24 text-left text-sm text-gray-600 mr-3">{{ $intelligence }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



