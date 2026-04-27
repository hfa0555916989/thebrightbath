@extends('layouts.public')

@section('title', 'حجز موعد مع ' . $consultant->user->name)

@push('analytics')
<script>
    // تتبع صفحة المستشار
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'صفحة مستشار - {{ $consultant->user->name }}',
                'page_location': window.location.href,
                'custom_page_name': 'صفحة حجز مستشار: {{ $consultant->user->name }}'
            });
            
            // حدث عرض خدمة
            gtag('event', 'view_item', {
                'currency': 'SAR',
                'value': {{ $consultant->price_per_hour ?? 0 }},
                'items': [{
                    'item_id': '{{ $consultant->id }}',
                    'item_name': 'استشارة مع {{ $consultant->user->name }}',
                    'item_category': 'استشارات',
                    'item_category2': '{{ $consultant->specialization_ar ?? "عام" }}',
                    'price': {{ $consultant->price_per_hour ?? 0 }}
                }]
            });
        }
    });
</script>
@endpush

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            
            {{-- Consultant Info Card --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    @if($consultant->photo)
                        <img src="{{ asset('storage/' . $consultant->photo) }}" alt="{{ $consultant->user->name }}" 
                             class="w-32 h-32 rounded-2xl object-cover shadow-lg">
                    @else
                        <div class="w-32 h-32 rounded-2xl bg-brand-gold/10 flex items-center justify-center shadow-lg">
                            <i class="fas fa-user text-5xl text-brand-gold"></i>
                        </div>
                    @endif
                    <div class="flex-1 text-center md:text-right">
                        <h1 class="text-2xl font-bold text-brand-dark mb-2">{{ $consultant->user->name }}</h1>
                        <p class="text-brand-gold font-medium text-lg mb-3">{{ $consultant->specialization_ar }}</p>
                        @if($consultant->bio_ar)
                        <p class="text-gray-600 mb-4">{{ $consultant->bio_ar }}</p>
                        @endif
                        <div class="flex flex-wrap justify-center md:justify-start gap-4">
                            <span class="px-4 py-2 bg-brand-gold/10 rounded-full text-sm">
                                <i class="fas fa-briefcase text-brand-gold ml-1"></i>
                                {{ $consultant->experience_years }} سنوات خبرة
                            </span>
                            <span class="px-4 py-2 bg-green-100 rounded-full text-sm text-green-700">
                                <i class="fas fa-star text-yellow-500 ml-1"></i>
                                {{ $consultant->rating }} ({{ $consultant->reviews_count }} تقييم)
                            </span>
                            <span class="px-4 py-2 bg-blue-100 rounded-full text-sm text-blue-700">
                                <i class="fas fa-users ml-1"></i>
                                {{ $consultant->total_sessions }} جلسة
                            </span>
                        </div>
                    </div>
                    <div class="text-center bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-2">أسعار الجلسات</p>
                        <div class="space-y-2">
                            <div>
                                <span class="text-2xl font-bold text-brand-gold">{{ number_format($consultant->price_per_30_min) }}</span>
                                <span class="text-sm text-gray-500">ر.س / 30 دقيقة</span>
                            </div>
                            <div>
                                <span class="text-2xl font-bold text-brand-DEFAULT">{{ number_format($consultant->price_per_60_min) }}</span>
                                <span class="text-sm text-gray-500">ر.س / ساعة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Booking Form --}}
            <div class="bg-white rounded-2xl shadow-lg p-6" x-data="{ duration: '30', selectedDate: '', selectedSlot: '' }" x-init="$watch('selectedDate', () => selectedSlot = '')">
                <h2 class="text-xl font-bold text-brand-dark mb-6 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-brand-gold"></i>
                    اختر موعد الجلسة
                </h2>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                @if(empty($availableSlots))
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-600 mb-2">لا توجد مواعيد متاحة حالياً</h3>
                    <p class="text-gray-500">يرجى التواصل مع المستشار مباشرة أو المحاولة لاحقاً</p>
                </div>
                @else
                <form action="{{ route('consultations.book', $consultant) }}" method="POST">
                    @csrf

                    {{-- Duration Selection --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">مدة الجلسة</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="duration" value="30" x-model="duration" class="sr-only peer">
                                <div class="p-4 border-2 rounded-xl text-center peer-checked:border-brand-gold peer-checked:bg-brand-gold/5 transition">
                                    <p class="text-2xl font-bold text-brand-dark">30 دقيقة</p>
                                    <p class="text-brand-gold font-bold">{{ number_format($consultant->price_per_30_min) }} ر.س</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="duration" value="60" x-model="duration" class="sr-only peer">
                                <div class="p-4 border-2 rounded-xl text-center peer-checked:border-brand-gold peer-checked:bg-brand-gold/5 transition">
                                    <p class="text-2xl font-bold text-brand-dark">60 دقيقة</p>
                                    <p class="text-brand-gold font-bold">{{ number_format($consultant->price_per_60_min) }} ر.س</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Date Selection --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">اختر التاريخ</label>
                        <div class="flex gap-3 overflow-x-auto pb-4" style="-webkit-overflow-scrolling: touch;">
                            @foreach($availableSlots as $index => $day)
                            <label class="flex-shrink-0 cursor-pointer">
                                <input type="radio" name="booking_date" value="{{ $day['date'] }}" 
                                       x-model="selectedDate" @change="selectedSlot = ''" class="sr-only peer">
                                <div class="w-24 p-3 border-2 rounded-xl text-center peer-checked:border-brand-gold peer-checked:bg-brand-gold/5 transition">
                                    <p class="text-sm text-gray-500">{{ $day['day_name'] }}</p>
                                    <p class="text-lg font-bold text-brand-dark">{{ \Carbon\Carbon::parse($day['date'])->format('d') }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($day['date'])->format('M') }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Time Selection --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">اختر الوقت</label>
                        
                        {{-- Message when no date selected --}}
                        <div x-show="!selectedDate" class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                            <i class="fas fa-info-circle text-blue-500 ml-2"></i>
                            <span class="text-blue-700">اختر التاريخ أولاً لعرض الأوقات المتاحة</span>
                        </div>
                        
                        {{-- Time slots --}}
                        <div x-show="selectedDate" x-transition class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                            @foreach($availableSlots as $day)
                            <template x-if="selectedDate === '{{ $day['date'] }}'">
                                <div class="contents">
                                    @foreach($day['slots'] as $slot)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="start_time" value="{{ $slot['start'] }}" 
                                               x-model="selectedSlot" class="sr-only peer">
                                        <div class="p-3 border-2 rounded-xl text-center peer-checked:border-brand-gold peer-checked:bg-brand-gold peer-checked:text-brand-dark transition text-sm font-medium hover:border-brand-gold/50">
                                            {{ $slot['formatted'] }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </template>
                            @endforeach
                        </div>
                        
                        {{-- Show number of available slots --}}
                        @foreach($availableSlots as $day)
                        <p x-show="selectedDate === '{{ $day['date'] }}'" x-transition class="text-sm text-gray-500 mt-2 text-center">
                            <i class="fas fa-clock text-brand-gold ml-1"></i>
                            {{ count($day['slots']) }} موعد متاح
                        </p>
                        @endforeach
                    </div>

                    {{-- Notes --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">ملاحظات (اختياري)</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold"
                                  placeholder="أخبر المستشار بما تريد مناقشته..."></textarea>
                    </div>

                    {{-- Summary --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-6" x-show="selectedDate && selectedSlot" x-transition>
                        <h3 class="font-bold text-brand-dark mb-3">ملخص الحجز</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">المستشار:</span>
                                <span class="font-medium">{{ $consultant->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">التاريخ:</span>
                                <span class="font-medium" x-text="selectedDate"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الوقت:</span>
                                <span class="font-medium" x-text="selectedSlot"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">المدة:</span>
                                <span class="font-medium" x-text="duration + ' دقيقة'"></span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="font-bold text-brand-dark">الإجمالي:</span>
                                <span class="font-bold text-brand-gold text-lg" x-text="(duration == 30 ? {{ $consultant->price_per_30_min }} : {{ $consultant->price_per_60_min }}) + ' ر.س'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" 
                            class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!selectedDate || !selectedSlot">
                        <i class="fas fa-credit-card ml-2"></i>
                        متابعة للدفع
                    </button>
                </form>
                @endif
            </div>

        </div>
    </div>
</section>

@endsection




