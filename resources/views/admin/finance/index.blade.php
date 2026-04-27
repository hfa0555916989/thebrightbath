@extends('layouts.admin')

@section('title', 'التقارير المالية')
@section('page-title', 'التقارير المالية')

@section('content')
<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-brand-dark">{{ number_format($todayRevenue) }}</p>
                    <p class="text-gray-500">إيرادات اليوم (ر.س)</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-brand-dark">{{ number_format($monthRevenue) }}</p>
                    <p class="text-gray-500">إيرادات الشهر (ر.س)</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-brand-gold/10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-2xl text-brand-gold"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-brand-dark">{{ number_format($totalRevenue) }}</p>
                    <p class="text-gray-500">إجمالي الإيرادات (ر.س)</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-percentage text-2xl text-purple-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-brand-dark">{{ number_format($platformEarnings) }}</p>
                    <p class="text-gray-500">أرباح المنصة ({{ $commissionRate }}%)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Payments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-4">آخر المدفوعات</h3>
            <div class="space-y-3">
                @forelse($recentPayments->take(10) as $payment)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-brand-dark">{{ $payment->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <span class="font-bold {{ $payment->status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ number_format($payment->amount) }} ر.س
                    </span>
                </div>
                @empty
                <p class="text-gray-400 text-center py-4">لا توجد مدفوعات</p>
                @endforelse
            </div>
        </div>

        {{-- Consultant Earnings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-4">أرباح المستشارين</h3>
            <div class="space-y-3">
                @forelse($consultantEarnings as $earning)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-brand-dark">{{ $earning->consultant->user->name ?? 'مستشار' }}</p>
                        <p class="text-sm text-gray-500">{{ $earning->sessions_count }} جلسة</p>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-brand-gold">{{ number_format($earning->total_earnings * (1 - $commissionRate/100)) }} ر.س</p>
                        <p class="text-xs text-gray-500">بعد خصم العمولة</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-center py-4">لا توجد أرباح</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Settings Link --}}
    <div class="text-center">
        <a href="{{ route('admin.finance.settings') }}" class="text-brand-gold hover:underline">
            <i class="fas fa-cog ml-1"></i> إعدادات العمولة
        </a>
    </div>
</div>
@endsection




