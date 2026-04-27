@extends('layouts.consultant')

@section('title', 'طلبات الحجز المعلقة')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📋 طلبات الحجز المعلقة</h1>
                <p class="text-gray-500 mt-1">طلبات تحتاج موافقتك قبل إتمام الدفع</p>
            </div>
            <a href="{{ route('consultant.dashboard') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للوحة التحكم
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($pendingBookings->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">لا توجد طلبات معلقة</h3>
                <p class="text-gray-500">ستظهر هنا طلبات الحجز الجديدة التي تحتاج موافقتك</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pendingBookings as $booking)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                {{-- Client Info --}}
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-gradient-to-br from-brand-gold to-amber-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                        {{ mb_substr($booking->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">{{ $booking->user->name }}</h3>
                                        <p class="text-gray-500 text-sm">{{ $booking->user->email }}</p>
                                        <p class="text-gray-500 text-sm">{{ $booking->user->phone ?? 'لا يوجد رقم' }}</p>
                                    </div>
                                </div>
                                
                                {{-- Booking Details --}}
                                <div class="flex flex-wrap gap-4 lg:gap-8">
                                    <div class="text-center">
                                        <p class="text-gray-500 text-xs mb-1">التاريخ</p>
                                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-500 text-xs mb-1">الوقت</p>
                                        <p class="font-semibold text-gray-800" dir="ltr">{{ date('h:i A', strtotime($booking->start_time)) }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-500 text-xs mb-1">المدة</p>
                                        <p class="font-semibold text-gray-800">{{ $booking->duration_minutes }} دقيقة</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-500 text-xs mb-1">المبلغ</p>
                                        <p class="font-bold text-brand-gold text-lg">{{ number_format($booking->price, 2) }} ر.س</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($booking->client_notes)
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-500 text-xs mb-1">ملاحظات العميل:</p>
                                    <p class="text-gray-700">{{ $booking->client_notes }}</p>
                                </div>
                            @endif
                            
                            {{-- Actions --}}
                            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                <form action="{{ route('consultant.booking.approve', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition flex items-center justify-center gap-2">
                                        <i class="fas fa-check"></i>
                                        قبول الحجز
                                    </button>
                                </form>
                                
                                <button type="button" 
                                        onclick="showRejectModal('{{ $booking->id }}')"
                                        class="flex-1 px-6 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    رفض الحجز
                                </button>
                            </div>
                        </div>
                        
                        {{-- Footer --}}
                        <div class="px-6 py-3 bg-gray-50 border-t flex items-center justify-between text-sm">
                            <span class="text-gray-500">رقم الحجز: <span class="font-mono">{{ $booking->booking_number }}</span></span>
                            <span class="text-gray-500">تاريخ الطلب: {{ $booking->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideRejectModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-6 m-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">رفض طلب الحجز</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">سبب الرفض (اختياري)</label>
                    <textarea name="reason" rows="3" 
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="مثال: الموعد غير متاح، لدي التزام آخر..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="hideRejectModal()" 
                            class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 transition">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition">
                        تأكيد الرفض
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(bookingId) {
    document.getElementById('rejectForm').action = '/consultant/booking/' + bookingId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection


