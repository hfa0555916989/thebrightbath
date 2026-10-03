@extends('layouts.admin')

@section('title', 'إعدادات الدفع - مصرف الراجحي')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" dir="rtl">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إعدادات بوابة الدفع</h1>
                    <p class="mt-2 text-gray-600">ربط بوابة الدفع الإلكتروني لمصرف الراجحي (نيوليب)</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.payment-settings.transactions') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        سجل المعاملات
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-500">إجمالي المعاملات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-500">معاملات ناجحة</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['successful']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-500">قيد الانتظار</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-emerald-100 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-500">إجمالي الإيرادات</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['total_amount'], 2) }} ر.س</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Settings Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50">
                        <h2 class="text-xl font-semibold text-gray-900">بوابة الراجحي (نيوليب)</h2>
                        <p class="text-sm text-gray-500">بيانات الربط التي يرسلها البنك إلى بريدك المسجل، أو تجدها في بوابة التاجر</p>
                    </div>

                    <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="p-6 space-y-6" autocomplete="off">
                        @csrf
                        @method('PUT')

                        @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 text-sm">{{ session('error') }}</div>
                        @endif
                        @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 text-sm space-y-1">
                            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                        </div>
                        @endif

                        <!-- Tranportal ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tranportal ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tranportal_id" dir="ltr"
                                   value="{{ old('tranportal_id', $settings?->tranportal_id) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono"
                                   placeholder="مثال: IPAYxxxxxxxxxxx">
                            <p class="mt-1 text-xs text-gray-500">معرّف الطرفية (Terminal) من بوابة التاجر</p>
                        </div>

                        <!-- Tranportal Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tranportal Password <span class="text-red-500">*</span>
                                @if($settings?->tranportal_password)
                                <span class="mr-2 text-xs font-normal text-green-600">✓ محفوظة</span>
                                @endif
                            </label>
                            <div class="relative">
                                <input type="password" name="tranportal_password" id="tranportal_password" dir="ltr" autocomplete="new-password"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono"
                                       placeholder="{{ $settings?->tranportal_password ? 'اتركه فارغًا للإبقاء على القيمة المحفوظة' : 'كلمة مرور الطرفية' }}">
                                <button type="button" onclick="togglePassword('tranportal_password')" class="absolute left-3 top-3 text-gray-400 hover:text-gray-600">👁</button>
                            </div>
                        </div>

                        <!-- Resource Key -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Resource Key <span class="text-red-500">*</span>
                                @if($settings?->resource_key)
                                <span class="mr-2 text-xs font-normal text-green-600">✓ محفوظ</span>
                                @endif
                            </label>
                            <div class="relative">
                                <input type="password" name="resource_key" id="resource_key" dir="ltr" autocomplete="new-password" maxlength="32"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono"
                                       placeholder="{{ $settings?->resource_key ? 'اتركه فارغًا للإبقاء على القيمة المحفوظة' : '32 حرفًا' }}">
                                <button type="button" onclick="togglePassword('resource_key')" class="absolute left-3 top-3 text-gray-400 hover:text-gray-600">👁</button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">مفتاح التشفير السري (32 حرفًا). لا تشاركه مع أحد.</p>
                        </div>

                        <!-- Endpoint URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                رابط بوابة الدفع (Payment Endpoint) <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="endpoint_url" dir="ltr"
                                   value="{{ old('endpoint_url', $settings?->endpoint_url) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                                   placeholder="https://...">
                            <p class="mt-1 text-xs text-gray-500">رابط "Bank Hosted" الذي يرسله البنك بالبريد. رابط بيئة الاختبار يختلف عن رابط الإنتاج.</p>
                        </div>

                        <!-- Mode -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البيئة</label>
                            <div class="flex gap-6">
                                <label class="flex items-center">
                                    <input type="radio" name="is_sandbox" value="1" {{ ($settings?->is_sandbox ?? true) ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="mr-2 text-sm">🧪 اختبار (UAT)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_sandbox" value="0" {{ !($settings?->is_sandbox ?? true) ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="mr-2 text-sm">🚀 إنتاج (Live)</span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">للتوضيح فقط. البيئة الفعلية يحددها رابط البوابة والبيانات المدخلة.</p>
                        </div>

                        <!-- Active Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900">تفعيل بوابة الدفع</h3>
                                <p class="text-sm text-gray-500">عند التفعيل يدفع العملاء عبر صفحة الدفع الآمنة لمصرف الراجحي</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $settings?->is_active ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:-translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-0.5 after:right-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all"></div>
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition">
                                حفظ الإعدادات
                            </button>
                            <button type="button" onclick="testConnection(this)" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                اختبار الاتصال
                            </button>
                        </div>
                        <p class="text-xs text-gray-500">اختبار الاتصال يستخدم البيانات المحفوظة، فاحفظ أولاً.</p>
                    </form>
                </div>
            </div>

            <!-- Help & Info -->
            <div class="space-y-6">
                <!-- URLs for the bank -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">روابط الاستجابة</h3>
                    <p class="text-sm text-gray-600 mb-4">تُرسل تلقائيًا مع كل عملية. إن طلب البنك تسجيلها مسبقًا فهذه هي:</p>

                    <p class="text-xs font-medium text-gray-500 mb-1">Response URL (والإشعارات)</p>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-2">
                        <code class="text-xs text-gray-800 break-all" id="responseUrl" dir="ltr">{{ route('payment.neoleap.response') }}</code>
                    </div>
                    <button type="button" onclick="copyText('responseUrl')" class="mb-4 text-sm text-indigo-600 hover:text-indigo-700">نسخ</button>

                    <p class="text-xs font-medium text-gray-500 mb-1">Error URL</p>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-2">
                        <code class="text-xs text-gray-800 break-all" id="errorUrl" dir="ltr">{{ route('payment.neoleap.error') }}</code>
                    </div>
                    <button type="button" onclick="copyText('errorUrl')" class="text-sm text-indigo-600 hover:text-indigo-700">نسخ</button>
                </div>

                <!-- Setup Guide -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">خطوات الإعداد</h3>
                    <ol class="space-y-3 text-sm text-gray-600 list-decimal pr-5">
                        <li>ادخل <a href="https://digitalpayments.alrajhibank.com.sa/mrchptl/merchant.htm" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">بوابة التاجر</a> وحمّل Tranportal ID وكلمة المرور وResource Key</li>
                        <li>أدخل البيانات ورابط بيئة الاختبار (UAT) ثم احفظ</li>
                        <li>اضغط "اختبار الاتصال"</li>
                        <li>فعّل البوابة ونفّذ دفعة تجريبية كاملة من حساب عميل</li>
                        <li>بعد اعتماد البنك للاختبار: بدّل إلى بيانات ورابط الإنتاج</li>
                    </ol>
                    <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                        <p class="font-medium text-gray-700 mb-1">بطاقة اختبار (من دليل البنك، لبيئة UAT فقط):</p>
                        <p dir="ltr" class="font-mono">4012 0010 3714 1112 · 12/2027 · CVV 212</p>
                    </div>
                </div>

                <!-- Status -->
                @if($settings && $settings->is_active)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="font-medium text-green-800">✓ بوابة الدفع مفعّلة</p>
                    <p class="text-sm text-green-600">{{ $settings->is_sandbox ? 'بيئة الاختبار' : 'بيئة الإنتاج' }}</p>
                </div>
                @else
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="font-medium text-amber-800">بوابة الدفع غير مفعّلة</p>
                    <p class="text-sm text-amber-600">أكمل بيانات الربط ثم فعّل البوابة</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Transactions -->
        @if($transactions->isNotEmpty())
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">آخر المعاملات</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">رقم المعاملة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $transaction->transaction_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $transaction->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $transaction->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $transaction->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function copyText(elementId) {
    const text = document.getElementById(elementId).textContent.trim();
    navigator.clipboard.writeText(text).then(() => alert('تم نسخ الرابط!'));
}

function testConnection(btn) {
    btn.disabled = true;
    btn.textContent = 'جاري الاختبار...';

    fetch('{{ route("admin.payment-settings.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => alert(data.message))
    .catch(() => alert('حدث خطأ في الاتصال'))
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'اختبار الاتصال';
    });
}
</script>
@endsection
