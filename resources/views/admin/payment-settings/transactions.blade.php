@extends('layouts.admin')

@section('title', 'سجل المعاملات المالية')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">سجل المعاملات المالية</h1>
                    <p class="mt-2 text-gray-600">جميع عمليات الدفع عبر بوابة Paymob</p>
                </div>
                <a href="{{ route('admin.payment-settings.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    إعدادات الدفع
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="رقم المعاملة، البريد الإلكتروني..."
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">الكل</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>ناجحة</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فاشلة</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    بحث
                </button>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.payment-settings.transactions') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                    إعادة تعيين
                </a>
                @endif
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($transactions->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-500 text-lg">لا توجد معاملات</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">رقم المعاملة</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">طريقة الدفع</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($transactions as $transaction)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="showDetails({{ $transaction->id }})">
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-900">{{ $transaction->transaction_id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-500">{{ $transaction->order_id ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($transaction->amount, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ $transaction->currency }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($transaction->card_type)
                                    <span class="text-sm text-gray-700">{{ ucfirst($transaction->card_type) }}</span>
                                    @if($transaction->card_last_four)
                                    <span class="text-xs text-gray-400 mr-1">•••• {{ $transaction->card_last_four }}</span>
                                    @endif
                                    @else
                                    <span class="text-sm text-gray-500">{{ $transaction->payment_method ?? '-' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @if($transaction->customer_email)
                                    <div class="text-gray-900">{{ $transaction->customer_email }}</div>
                                    @endif
                                    @if($transaction->customer_phone)
                                    <div class="text-gray-500 text-xs">{{ $transaction->customer_phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $transaction->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $transaction->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $transaction->status === 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $transaction->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $transaction->created_at->format('Y-m-d') }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $transactions->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-auto">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">تفاصيل المعاملة</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-6">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>

<script>
function showDetails(id) {
    // For now, just show a simple alert - you can enhance this to show full details
    alert('تفاصيل المعاملة رقم: ' + id);
}

function closeModal() {
    document.getElementById('detailsModal').classList.add('hidden');
    document.getElementById('detailsModal').classList.remove('flex');
}
</script>
@endsection
