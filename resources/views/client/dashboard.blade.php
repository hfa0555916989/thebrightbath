@extends('layouts.public')

@section('title', 'لوحة التحكم')

@section('content')
<section class="pt-28 pb-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-l from-brand-dark to-brand-DEFAULT rounded-2xl p-8 mb-8 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">مرحباً، {{ $user->name }}</h1>
                    <p class="text-gray-300">مرحباً بك في لوحة التحكم الخاصة بك</p>
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="bg-white/10 hover:bg-white/20 px-6 py-3 rounded-xl transition flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-brand-gold/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-xl text-brand-gold"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-brand-dark">{{ $stats['total_assessments'] }}</p>
                        <p class="text-sm text-gray-500">اختبار مكتمل</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-video text-xl text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-brand-dark">{{ $stats['total_sessions'] }}</p>
                        <p class="text-sm text-gray-500">جلسة مكتملة</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-xl text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-brand-dark">{{ $stats['upcoming_sessions'] }}</p>
                        <p class="text-sm text-gray-500">جلسة قادمة</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-wallet text-xl text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-brand-dark">{{ number_format($stats['total_spent']) }}</p>
                        <p class="text-sm text-gray-500">ر.س إجمالي</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Upcoming Sessions --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-brand-dark flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-brand-gold"></i>
                            الجلسات القادمة
                        </h2>
                        <a href="{{ route('client.sessions') }}" class="text-brand-gold hover:underline text-sm">عرض الكل</a>
                    </div>

                    @if($upcomingBookings->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 mb-4">لا توجد جلسات قادمة</p>
                        <a href="{{ route('consultations.index') }}" class="text-brand-gold hover:underline">احجز جلسة الآن</a>
                    </div>
                    @else
                    <div class="space-y-4">
                        @foreach($upcomingBookings as $booking)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-4">
                                @if($booking->consultant->photo)
                                    <img src="{{ asset('storage/' . $booking->consultant->photo) }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-brand-gold/20 flex items-center justify-center">
                                        <i class="fas fa-user text-brand-gold"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-brand-dark">{{ $booking->consultant->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->booking_date->format('Y-m-d') }} - {{ date('h:i A', strtotime($booking->start_time)) }}</p>
                                </div>
                            </div>
                            @if($booking->status === 'confirmed' || $booking->status === 'paid')
                            <a href="{{ route('video-call.join', $booking) }}" class="bg-brand-gold text-brand-dark px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-video ml-1"></i> انضم
                            </a>
                            @elseif($booking->status === 'approved')
                            <a href="{{ route('consultations.payment', $booking) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium animate-pulse">
                                <i class="fas fa-credit-card ml-1"></i> ادفع الآن
                            </a>
                            @elseif($booking->status === 'pending_approval')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg text-sm">
                                <i class="fas fa-clock ml-1"></i> بانتظار الموافقة
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Assessment Results --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-brand-dark flex items-center gap-2">
                            <i class="fas fa-chart-pie text-brand-gold"></i>
                            نتائج الاختبارات
                        </h2>
                        <a href="{{ route('client.results') }}" class="text-brand-gold hover:underline text-sm">عرض الكل</a>
                    </div>

                    @if($assessments->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clipboard-list text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 mb-4">لم تقم بأي اختبار بعد</p>
                        <a href="{{ route('assessments.index') }}" class="text-brand-gold hover:underline">ابدأ أول اختبار</a>
                    </div>
                    @else
                    <div class="space-y-3">
                        @foreach($assessments as $assessment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-bold text-brand-dark">{{ $assessment->assessment_name }}</p>
                                <p class="text-sm text-gray-500">{{ $assessment->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="text-left">
                                <span class="px-4 py-2 bg-brand-gold/10 text-brand-gold font-bold rounded-lg">{{ $assessment->type_code }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Quick Links --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-brand-dark mb-4">روابط سريعة</h3>
                    <div class="space-y-2">
                        <a href="{{ route('assessments.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-clipboard-list text-brand-gold w-5"></i>
                            <span>اختبارات الميول</span>
                        </a>
                        <a href="{{ route('consultations.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-user-tie text-brand-gold w-5"></i>
                            <span>حجز استشارة</span>
                        </a>
                        <a href="{{ route('client.sessions') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-calendar text-brand-gold w-5"></i>
                            <span>جلساتي</span>
                        </a>
                        <a href="{{ route('client.invoices') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-file-invoice text-brand-gold w-5"></i>
                            <span>فواتيري</span>
                        </a>
                        <a href="{{ route('client.profile') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-user-cog text-brand-gold w-5"></i>
                            <span>الملف الشخصي</span>
                        </a>
                    </div>
                </div>

                {{-- Recent Payments --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-brand-dark">آخر المدفوعات</h3>
                        <a href="{{ route('client.invoices') }}" class="text-brand-gold text-sm">الكل</a>
                    </div>
                    @if($recentPayments->isEmpty())
                    <p class="text-gray-500 text-sm text-center py-4">لا توجد مدفوعات</p>
                    @else
                    <div class="space-y-3">
                        @foreach($recentPayments as $payment)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $payment->created_at->format('m/d') }}</span>
                            <span class="font-bold {{ $payment->status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ number_format($payment->amount) }} ر.س
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Need Help --}}
                <div class="bg-gradient-to-bl from-brand-gold to-brand-orange rounded-xl p-6 text-brand-dark">
                    <h3 class="font-bold mb-2">هل تحتاج مساعدة؟</h3>
                    <p class="text-sm mb-4 opacity-80">تواصل معنا مباشرة عبر واتساب</p>
                    <a href="https://wa.me/966543494316" target="_blank" 
                       class="flex items-center justify-center gap-2 bg-white text-brand-dark py-3 rounded-xl font-bold hover:bg-gray-100 transition">
                        <i class="fab fa-whatsapp text-green-500"></i>
                        تواصل معنا
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection




