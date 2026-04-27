@extends('layouts.consultant')

@section('title', 'لوحة تحكم المستشار')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-l from-primary-600 to-primary-800 rounded-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">مرحباً {{ $consultant->user->name ?? 'المستشار' }}! 👋</h1>
                    <p class="text-primary-100">{{ $consultant->specialization }}</p>
                </div>
                <div class="text-left">
                    <div class="flex items-center text-sm">
                        <span class="ml-2">⭐</span>
                        <span>{{ number_format($consultant->rating, 1) }}</span>
                        <span class="text-primary-200 mr-1">({{ $consultant->reviews_count }} تقييم)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Today's Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                        <span class="text-2xl">📅</span>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">جلسات اليوم</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $todayBookings->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Earnings -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-4">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">أرباح الشهر</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($monthlyEarnings, 0) }} ر.س</p>
                    </div>
                </div>
            </div>

            <!-- Total Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center ml-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الجلسات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSessions }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                @php
                    $pendingRequestsCount = \App\Models\Booking::where('consultant_id', $consultant->id)
                        ->where('status', 'pending_approval')
                        ->count();
                @endphp
                <div class="flex items-center">
                    <div class="w-12 h-12 {{ $pendingRequestsCount > 0 ? 'bg-orange-100' : 'bg-gray-100' }} rounded-lg flex items-center justify-center ml-4">
                        <span class="text-2xl">📨</span>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">طلبات معلقة</p>
                        <p class="text-2xl font-bold {{ $pendingRequestsCount > 0 ? 'text-orange-600' : 'text-gray-900' }}">{{ $pendingRequestsCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Today's Sessions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">📅 جلسات اليوم</h2>
                </div>
                <div class="p-6">
                    @if($todayBookings->isEmpty())
                        <div class="text-center py-8">
                            <span class="text-4xl mb-4 block">🎉</span>
                            <p class="text-gray-500">لا توجد جلسات مجدولة لليوم</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($todayBookings as $booking)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center ml-3">
                                            <span class="text-primary-600 font-bold">{{ mb_substr($booking->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $booking->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('video-call.join', $booking) }}" class="bg-brand-gold text-brand-dark px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-brand-goldDeep transition">
                                            <i class="fas fa-video ml-1"></i> انضم
                                        </a>
                                        <a href="{{ route('consultant.client-details', $booking) }}" class="text-primary-600 hover:text-primary-700 text-sm">
                                            ←
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Sessions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">📆 الجلسات القادمة</h2>
                </div>
                <div class="p-6">
                    @if($upcomingBookings->isEmpty())
                        <div class="text-center py-8">
                            <span class="text-4xl mb-4 block">📭</span>
                            <p class="text-gray-500">لا توجد جلسات قادمة</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($upcomingBookings as $booking)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center ml-3">
                                            <span class="text-blue-600 font-bold">{{ mb_substr($booking->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $booking->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($booking->status === 'confirmed')
                                        <a href="{{ route('video-call.join', $booking) }}" class="bg-green-500 text-white px-2 py-1 rounded text-xs font-medium hover:bg-green-600 transition">
                                            <i class="fas fa-video"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('consultant.client-details', $booking) }}" class="text-primary-600 hover:text-primary-700 text-sm">
                                            ←
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $pendingCount = \App\Models\Booking::where('consultant_id', $consultant->id)
                    ->where('status', 'pending_approval')
                    ->count();
            @endphp
            <a href="{{ route('consultant.pending-requests') }}" class="relative flex items-center justify-center p-4 bg-white rounded-xl shadow-sm border-2 {{ $pendingCount > 0 ? 'border-amber-400 bg-amber-50' : 'border-gray-100' }} hover:border-primary-300 transition">
                @if($pendingCount > 0)
                    <span class="absolute -top-2 -left-2 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">{{ $pendingCount }}</span>
                @endif
                <span class="text-2xl ml-3">📨</span>
                <span class="font-semibold text-gray-700 text-sm md:text-base">طلبات الحجز</span>
            </a>
            <a href="{{ route('consultant.schedule') }}" class="flex items-center justify-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-primary-300 transition">
                <span class="text-2xl ml-3">⏰</span>
                <span class="font-semibold text-gray-700 text-sm md:text-base">إدارة المواعيد</span>
            </a>
            <a href="{{ route('consultant.earnings') }}" class="flex items-center justify-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-primary-300 transition">
                <span class="text-2xl ml-3">💵</span>
                <span class="font-semibold text-gray-700 text-sm md:text-base">تقرير الأرباح</span>
            </a>
            <a href="{{ route('consultant.sessions') }}" class="flex items-center justify-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-primary-300 transition">
                <span class="text-2xl ml-3">📋</span>
                <span class="font-semibold text-gray-700 text-sm md:text-base">جميع الجلسات</span>
            </a>
            <a href="{{ route('consultant.profile') }}" class="flex items-center justify-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-primary-300 transition">
                <span class="text-2xl ml-3">👤</span>
                <span class="font-semibold text-gray-700 text-sm md:text-base">الملف الشخصي</span>
            </a>
        </div>
    </div>
</div>
@endsection

