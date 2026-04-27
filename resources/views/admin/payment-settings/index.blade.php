@extends('layouts.admin')

@section('title', 'إعدادات الدفع - Paymob')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" dir="rtl">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إعدادات بوابة الدفع</h1>
                    <p class="mt-2 text-gray-600">إعداد وربط بوابة Paymob للدفع الإلكتروني</p>
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
                        <div class="flex items-center">
                            <img src="https://paymob.com/images/paymobLogo.png" alt="Paymob" class="h-8 ml-3" onerror="this.style.display='none'">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">إعدادات Paymob</h2>
                                <p class="text-sm text-gray-500">أدخل بيانات حساب Paymob الخاص بك</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- V2 API Notice -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Paymob KSA - V2 Intention API</p>
                                    <p class="text-xs text-blue-600 mt-1">هذا النظام يستخدم V2 Unified Intention API الخاص بالسعودية</p>
                                </div>
                            </div>
                        </div>

                        <!-- Secret Key (Required) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Secret Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="secret_key" 
                                       id="secret_key"
                                       value="{{ $settings?->secret_key }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="أدخل المفتاح السري هنا"
                                       required>
                                <button type="button" onclick="togglePassword('secret_key')" class="absolute left-3 top-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">تجدها في <a href="https://ksa.paymob.com" target="_blank" class="text-indigo-600 hover:underline">Dashboard</a> → Profile → Secret Key</p>
                        </div>

                        <!-- Public Key (Required for Checkout) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Public Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="public_key" 
                                       id="public_key"
                                       value="{{ $settings?->public_key }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="pk_live_xxxxxxxxxxxxxxxxxxxxxxxx"
                                       required>
                                <button type="button" onclick="togglePassword('public_key')" class="absolute left-3 top-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">تجدها في Dashboard → Profile → Public Key</p>
                        </div>

                        <!-- Integration ID (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Integration ID(s)
                                <span class="text-gray-400 text-xs font-normal">(اختياري)</span>
                            </label>
                            <input type="text" 
                                   name="integration_id" 
                                   value="{{ $settings?->integration_id }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="مثال: 123456 أو 123456,789012 لعدة طرق دفع">
                            <p class="mt-1 text-xs text-gray-500">تجدها في Payment Integrations - يمكن إدخال عدة IDs مفصولة بفاصلة</p>
                        </div>

                        <!-- HMAC Secret -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                HMAC Secret
                                <span class="text-gray-400 text-xs font-normal">(للتحقق من Webhooks)</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="hmac_secret"
                                       id="hmac_secret" 
                                       value="{{ $settings?->hmac_secret }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="للتحقق من صحة الـ Webhooks">
                                <button type="button" onclick="togglePassword('hmac_secret')" class="absolute left-3 top-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">تجدها في Dashboard → Profile → HMAC Secret</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Currency -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">العملة</label>
                                <select name="currency" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="SAR" {{ ($settings?->currency ?? 'SAR') == 'SAR' ? 'selected' : '' }}>🇸🇦 ريال سعودي (SAR)</option>
                                    <option value="EGP" {{ $settings?->currency == 'EGP' ? 'selected' : '' }}>🇪🇬 جنيه مصري (EGP)</option>
                                    <option value="AED" {{ $settings?->currency == 'AED' ? 'selected' : '' }}>🇦🇪 درهم إماراتي (AED)</option>
                                    <option value="USD" {{ $settings?->currency == 'USD' ? 'selected' : '' }}>🇺🇸 دولار أمريكي (USD)</option>
                                </select>
                            </div>

                            <!-- Mode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وضع التشغيل</label>
                                <div class="flex gap-4 mt-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_sandbox" value="1" {{ ($settings?->is_sandbox ?? true) ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="mr-2 text-sm">🧪 تجريبي (Sandbox)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="is_sandbox" value="0" {{ !($settings?->is_sandbox ?? true) ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="mr-2 text-sm">🚀 حقيقي (Live)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Active Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900">تفعيل بوابة الدفع</h3>
                                <p class="text-sm text-gray-500">عند التفعيل، سيتمكن العملاء من الدفع عبر Paymob</p>
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
                            <button type="button" onclick="testConnection()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                اختبار الاتصال
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help & Info -->
            <div class="space-y-6">
                <!-- Webhook URL -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        رابط Webhook
                    </h3>
                    <p class="text-sm text-gray-600 mb-3">انسخ هذا الرابط وأضفه في إعدادات Paymob:</p>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <code class="text-xs text-gray-800 break-all" id="webhookUrl">{{ url('/api/paymob/callback') }}</code>
                    </div>
                    <button onclick="copyWebhook()" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 flex items-center">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                        نسخ الرابط
                    </button>
                </div>

                <!-- Setup Guide -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        خطوات الإعداد (V2 KSA)
                    </h3>
                    <ol class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold ml-2">1</span>
                            <span>سجّل في <a href="https://ksa.paymob.com" target="_blank" class="text-indigo-600 hover:underline">Paymob KSA</a></span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold ml-2">2</span>
                            <span>اذهب إلى Profile للحصول على Secret Key و Public Key</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold ml-2">3</span>
                            <span>أنشئ Payment Integration للحصول على Integration ID</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold ml-2">4</span>
                            <span>انسخ HMAC Secret من Profile</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold ml-2">5</span>
                            <span>أضف رابط Webhook في Dashboard</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold ml-2">6</span>
                            <span>اختبر الاتصال ثم فعّل البوابة</span>
                        </li>
                    </ol>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="https://developers.paymob.com/ksa" target="_blank" class="text-sm text-indigo-600 hover:underline flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            التوثيق الرسمي
                        </a>
                    </div>
                </div>

                <!-- Status -->
                @if($settings && $settings->is_active)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-green-800">بوابة الدفع مفعّلة</p>
                            <p class="text-sm text-green-600">{{ $settings->is_sandbox ? 'الوضع التجريبي' : 'الوضع الحقيقي' }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-amber-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-amber-800">بوابة الدفع غير مفعّلة</p>
                            <p class="text-sm text-amber-600">أكمل الإعدادات ثم فعّل البوابة</p>
                        </div>
                    </div>
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

function copyWebhook() {
    const url = document.getElementById('webhookUrl').textContent;
    navigator.clipboard.writeText(url).then(() => {
        alert('تم نسخ الرابط!');
    });
}

function testConnection() {
    const btn = event.target;
    btn.disabled = true;
    btn.textContent = 'جاري الاختبار...';

    fetch('{{ route("admin.payment-settings.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => {
        alert('حدث خطأ في الاتصال');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'اختبار الاتصال';
    });
}
</script>
@endsection
