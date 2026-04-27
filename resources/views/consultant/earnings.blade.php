@extends('layouts.consultant')

@section('title', 'تقرير الأرباح')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">💵 تقرير الأرباح</h1>
            <p class="text-gray-500 mt-1">تتبع أرباحك من الجلسات الاستشارية</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-l from-green-500 to-green-600 rounded-2xl p-6 text-white">
                <p class="text-green-100 text-sm">إجمالي الأرباح</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalEarnings, 2) }} ر.س</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm">نسبة العمولة</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ 100 - $consultant->commission_rate }}%</p>
                <p class="text-sm text-gray-400">نسبتك من كل جلسة</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm">سعر الجلسة</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($consultant->hourly_rate, 0) }} ر.س</p>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">📋 آخر المدفوعات</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">العميل</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">التاريخ</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">قيمة الجلسة</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold text-gray-600">أرباحك</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center ml-3">
                                            <span class="text-primary-600 font-bold text-sm">{{ mb_substr($payment->user->name, 0, 1) }}</span>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $payment->user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-600">{{ $payment->booking_date->format('Y/m/d') }}</td>
                                <td class="py-4 px-6 text-gray-600">{{ number_format($payment->price, 2) }} ر.س</td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-green-600">{{ number_format($payment->consultant_earnings ?? $payment->price * 0.8, 2) }} ر.س</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500">
                                    <span class="text-4xl block mb-4">💰</span>
                                    لا توجد مدفوعات حتى الآن
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



