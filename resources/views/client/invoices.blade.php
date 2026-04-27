@extends('layouts.public')

@section('title', 'فواتيري')

@section('content')
<section class="pt-28 pb-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-brand-dark">فواتيري ومدفوعاتي</h1>
                <a href="{{ route('client.dashboard') }}" class="text-brand-gold hover:underline">
                    <i class="fas fa-arrow-right ml-1"></i> العودة
                </a>
            </div>

            @if($payments->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-invoice text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-600 mb-2">لا توجد فواتير</h2>
                <p class="text-gray-500">ستظهر هنا جميع مدفوعاتك وفواتيرك</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">رقم الفاتورة</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">الوصف</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">المبلغ</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">الحالة</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-sm">{{ $payment->payment_id }}</td>
                            <td class="px-6 py-4">
                                @if($payment->booking)
                                    جلسة استشارية مع {{ $payment->booking->consultant->user->name ?? 'مستشار' }}
                                @else
                                    {{ $payment->description ?? 'دفعة' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-brand-dark">{{ number_format($payment->amount) }} {{ $payment->currency }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    @if($payment->status === 'completed') bg-green-100 text-green-700
                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($payment->status === 'refunded') bg-blue-100 text-blue-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ $payment->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection




