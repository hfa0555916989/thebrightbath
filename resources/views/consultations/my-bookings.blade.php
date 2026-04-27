@extends('layouts.public')

@section('title', 'حجوزاتي')

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <h1 class="text-3xl font-bold text-brand-dark mb-8">حجوزاتي</h1>

            {{-- Upcoming Bookings --}}
            <div class="mb-12">
                <h2 class="text-xl font-bold text-brand-dark mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-brand-gold"></i>
                    الحجوزات القادمة
                </h2>

                @if($upcomingBookings->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 mb-4">لا توجد حجوزات قادمة</p>
                    <a href="{{ route('consultations.index') }}" class="text-brand-gold hover:underline">
                        احجز جلسة الآن
                    </a>
                </div>
                @else
                <div class="space-y-4">
                    @foreach($upcomingBookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                @if($booking->consultant->photo)
                                    <img src="{{ asset('storage/' . $booking->consultant->photo) }}" class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-brand-gold/10 flex items-center justify-center">
                                        <i class="fas fa-user text-2xl text-brand-gold"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-brand-dark">{{ $booking->consultant->user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $booking->consultant->specialization_ar }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-sm">
                                        <span class="text-brand-gold">
                                            <i class="fas fa-calendar ml-1"></i>
                                            {{ $booking->booking_date->format('Y-m-d') }}
                                        </span>
                                        <span class="text-gray-600">
                                            <i class="fas fa-clock ml-1"></i>
                                            {{ date('h:i A', strtotime($booking->start_time)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $booking->status_label }}
                                </span>
                                <div class="flex gap-2">
                                    @if($booking->status === 'confirmed')
                                    <a href="{{ route('meeting.join', $booking) }}" 
                                       class="px-4 py-2 bg-brand-gold text-brand-dark rounded-lg text-sm font-medium hover:bg-brand-goldDeep transition">
                                        <i class="fas fa-video ml-1"></i>
                                        انضم للجلسة
                                    </a>
                                    @endif
                                    @if($booking->canBeCancelled())
                                    <form action="{{ route('consultations.cancel', $booking) }}" method="POST" 
                                          onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition">
                                            إلغاء
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Past Bookings --}}
            <div>
                <h2 class="text-xl font-bold text-brand-dark mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-gray-400"></i>
                    الحجوزات السابقة
                </h2>

                @if($pastBookings->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <p class="text-gray-500">لا توجد حجوزات سابقة</p>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($pastBookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm p-4 opacity-75">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($booking->consultant->photo)
                                    <img src="{{ asset('storage/' . $booking->consultant->photo) }}" class="w-12 h-12 rounded-full object-cover grayscale">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-medium text-gray-700">{{ $booking->consultant->user->name }}</h3>
                                    <p class="text-sm text-gray-400">{{ $booking->booking_date->format('Y-m-d') }} - {{ date('h:i A', strtotime($booking->start_time)) }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $booking->status_label }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection




