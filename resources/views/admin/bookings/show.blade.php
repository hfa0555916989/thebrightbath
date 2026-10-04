@extends('layouts.admin')

@section('title', 'الحجز ' . $booking->booking_number)
@section('page-title', 'تفاصيل الحجز')

@section('content')
@php($refundStatus = $transaction?->gateway_response['refund_status'] ?? null)
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-500 hover:text-brand-gold">→ كل الحجوزات</a>
            <h2 class="text-2xl font-bold text-brand-dark mt-1 font-mono">{{ $booking->booking_number }}</h2>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700">{{ $booking->status_label }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Client --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-brand-dark mb-4">👤 العميل</h3>
            <p class="font-semibold">{{ $booking->user->name }}</p>
            <p class="text-sm text-gray-500" dir="ltr">{{ $booking->user->email }}</p>
            <p class="text-sm text-gray-500" dir="ltr">{{ $booking->user->phone }}</p>
            @if($booking->client_notes)
                <p class="text-sm text-gray-700 mt-4 bg-gray-50 rounded-lg p-3">{{ $booking->client_notes }}</p>
            @endif
        </div>

        {{-- Consultant --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-brand-dark mb-4">👨‍💼 المستشار</h3>
            <p class="font-semibold">{{ $booking->consultant->name }}</p>
            <p class="text-sm text-gray-500">{{ $booking->consultant->specialization_ar }}</p>
            <p class="text-sm text-gray-500" dir="ltr">{{ $booking->consultant->user->email ?? '' }}</p>
        </div>

        {{-- Session --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-brand-dark mb-4">📅 الجلسة</h3>
            <dl class="text-sm space-y-2">
                <div class="flex justify-between"><dt class="text-gray-500">التاريخ</dt><dd>{{ $booking->formatted_date }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">الوقت</dt><dd>{{ $booking->formatted_time }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">المدة</dt><dd>{{ $booking->duration_minutes }} دقيقة</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">السعر</dt><dd class="font-bold text-brand-gold">{{ number_format($booking->price, 2) }} ر.س</dd></div>
            </dl>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Payment & refund --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-brand-dark mb-4">💳 الدفع</h3>
            <dl class="text-sm space-y-2 mb-6">
                <div class="flex justify-between"><dt class="text-gray-500">حالة الدفع</dt><dd>{{ $booking->payment_status_label }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">تاريخ الدفع</dt><dd>{{ $booking->paid_at?->format('Y-m-d H:i') ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">رقم معاملة البنك</dt><dd class="font-mono" dir="ltr">{{ $transaction?->transaction_id ?? '—' }}</dd></div>
                @if($transaction?->card_last_four)
                <div class="flex justify-between"><dt class="text-gray-500">البطاقة</dt><dd dir="ltr">{{ $transaction->card_type }} •••• {{ $transaction->card_last_four }}</dd></div>
                @endif
                <div class="flex justify-between"><dt class="text-gray-500">ربح المستشار / الإدارة</dt><dd>{{ number_format($booking->consultant_earnings, 2) }} / {{ number_format($booking->admin_earnings, 2) }} ر.س</dd></div>
            </dl>

            @if($transaction && $transaction->status === 'success' && !in_array($refundStatus, ['refunded', 'processing', 'requested'], true))
                <form method="POST" action="{{ route('admin.payment-settings.refund', $transaction) }}"
                      onsubmit="return confirm('استرداد {{ number_format($transaction->amount, 2) }} ر.س كاملة إلى بطاقة العميل وإلغاء الحجز؟')">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition">
                        استرداد المبلغ للعميل وإلغاء الحجز
                    </button>
                </form>
                @if($refundStatus === 'failed')
                    <p class="text-xs text-red-600 mt-2">محاولة استرداد سابقة فشلت عبر البوابة. يمكنك إعادة المحاولة، أو تنفيذه من بوابة التاجر.</p>
                @endif
            @elseif($refundStatus === 'refunded' || $transaction?->status === 'refunded')
                <p class="text-sm text-green-700 bg-green-50 rounded-lg p-3">✓ تم استرداد المبلغ إلى بطاقة العميل.</p>
            @elseif($refundStatus === 'processing' || $refundStatus === 'requested')
                <p class="text-sm text-amber-700 bg-amber-50 rounded-lg p-3">طلب الاسترداد قيد المعالجة لدى البنك.</p>
            @elseif($booking->payment_status === 'paid')
                <p class="text-sm text-gray-500">لا توجد معاملة بوابة لهذا الحجز؛ أي استرداد يتم يدويًا.</p>
            @endif
        </div>

        {{-- Admin update --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-brand-dark mb-4">⚙️ تعديل الحجز</h3>
            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-lg">
                        @foreach(\App\Models\Booking::$statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($booking->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">تغيير الحالة لا يحرك أي مبالغ. للاسترداد استخدم زر الاسترداد.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="consultant_notes" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-lg">{{ $booking->consultant_notes }}</textarea>
                </div>
                <button type="submit" class="w-full bg-brand-gold text-brand-dark py-3 rounded-xl font-bold">حفظ</button>
            </form>
        </div>
    </div>
</div>
@endsection
