@extends('layouts.public')

@section('title', 'سياسة الخصوصية')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-brand-bg to-white py-12 px-4">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-brand-dark mb-4">سياسة الخصوصية</h1>
            <p class="text-gray-500">آخر تحديث: 12 ديسمبر 2025</p>
        </div>

        {{-- Content Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-10">
            {{-- مقدمة --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">🔒</span>
                    مقدمة
                </h2>
                <p class="text-gray-700 leading-relaxed">
                    في <strong>"الطريق المشرق للتدريب والتطوير"</strong>، نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية. توضح هذه السياسة كيفية جمع واستخدام وحماية معلوماتك عند استخدام منصتنا.
                </p>
            </section>

            {{-- البيانات التي نجمعها --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">📊</span>
                    البيانات التي نجمعها
                </h2>
                <div class="space-y-4 text-gray-700">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2 text-brand-DEFAULT">1. البيانات الشخصية</h3>
                        <ul class="list-disc list-inside space-y-1 mr-4">
                            <li>الاسم الكامل</li>
                            <li>البريد الإلكتروني</li>
                            <li>رقم الهاتف</li>
                            <li>معلومات الدفع (تُعالج بشكل آمن عبر مزودي الخدمة)</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2 text-brand-DEFAULT">2. بيانات الاستخدام</h3>
                        <ul class="list-disc list-inside space-y-1 mr-4">
                            <li>نتائج الاختبارات المهنية</li>
                            <li>سجل الجلسات الاستشارية</li>
                            <li>سجل التصفح داخل المنصة</li>
                            <li>عنوان IP والموقع التقريبي</li>
                            <li>نوع الجهاز والمتصفح</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2 text-brand-DEFAULT">3. ملفات تعريف الارتباط (Cookies)</h3>
                        <p>نستخدم ملفات تعريف الارتباط لتحسين تجربتك وتذكر تفضيلاتك. يمكنك التحكم في إعدادات الكوكيز من متصفحك.</p>
                    </div>
                </div>
            </section>

            {{-- كيف نستخدم بياناتك --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">⚙️</span>
                    كيف نستخدم بياناتك
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-brand-gold/10 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-gold">✓</span> تقديم الخدمات
                        </h3>
                        <p class="text-gray-600 text-sm">إدارة حسابك وتقديم الاختبارات والاستشارات</p>
                    </div>
                    <div class="bg-brand-gold/10 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-gold">✓</span> التواصل معك
                        </h3>
                        <p class="text-gray-600 text-sm">إرسال إشعارات الحجز والتأكيدات والتحديثات</p>
                    </div>
                    <div class="bg-brand-gold/10 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-gold">✓</span> تحسين الخدمات
                        </h3>
                        <p class="text-gray-600 text-sm">تحليل الاستخدام لتطوير وتحسين منصتنا</p>
                    </div>
                    <div class="bg-brand-gold/10 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-gold">✓</span> الأمان والحماية
                        </h3>
                        <p class="text-gray-600 text-sm">حماية حسابك ومنع الاحتيال والأنشطة المشبوهة</p>
                    </div>
                </div>
            </section>

            {{-- مشاركة البيانات --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">🤝</span>
                    مشاركة البيانات
                </h2>
                <div class="text-gray-700 space-y-4">
                    <p><strong>نحن لا نبيع بياناتك الشخصية.</strong> قد نشارك معلوماتك فقط في الحالات التالية:</p>
                    <div class="bg-gray-50 p-4 rounded-xl space-y-3">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-brand-DEFAULT text-white rounded-full flex items-center justify-center text-sm flex-shrink-0">1</span>
                            <div>
                                <strong>المستشارون:</strong> عند حجز جلسة، يُشارك اسمك ومعلومات التواصل مع المستشار المختار.
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-brand-DEFAULT text-white rounded-full flex items-center justify-center text-sm flex-shrink-0">2</span>
                            <div>
                                <strong>مزودو الخدمات:</strong> بوابات الدفع، خدمات الاستضافة، وخدمات البريد الإلكتروني.
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-brand-DEFAULT text-white rounded-full flex items-center justify-center text-sm flex-shrink-0">3</span>
                            <div>
                                <strong>المتطلبات القانونية:</strong> عند وجود أمر قضائي أو طلب من جهة حكومية مختصة.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- حماية البيانات --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">🛡️</span>
                    حماية البيانات
                </h2>
                <div class="text-gray-700">
                    <p class="mb-4">نتخذ إجراءات أمنية صارمة لحماية بياناتك:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-lock text-green-600"></i>
                            <span>تشفير SSL لجميع البيانات</span>
                        </div>
                        <div class="flex items-center gap-2 bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-server text-green-600"></i>
                            <span>خوادم آمنة ومحمية</span>
                        </div>
                        <div class="flex items-center gap-2 bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-user-shield text-green-600"></i>
                            <span>صلاحيات وصول محدودة</span>
                        </div>
                        <div class="flex items-center gap-2 bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-sync-alt text-green-600"></i>
                            <span>تحديثات أمنية دورية</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- حقوقك --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">👤</span>
                    حقوقك
                </h2>
                <div class="text-gray-700">
                    <p class="mb-4">لديك الحقوق التالية فيما يتعلق ببياناتك الشخصية:</p>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-xl">
                            <span class="text-brand-gold text-xl">📋</span>
                            <div>
                                <strong>حق الوصول:</strong> طلب نسخة من بياناتك الشخصية المحفوظة لدينا.
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-xl">
                            <span class="text-brand-gold text-xl">✏️</span>
                            <div>
                                <strong>حق التصحيح:</strong> طلب تصحيح أي بيانات غير دقيقة.
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-xl">
                            <span class="text-brand-gold text-xl">🗑️</span>
                            <div>
                                <strong>حق الحذف:</strong> طلب حذف حسابك وبياناتك (مع مراعاة المتطلبات القانونية).
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-xl">
                            <span class="text-brand-gold text-xl">🚫</span>
                            <div>
                                <strong>حق الاعتراض:</strong> الاعتراض على استخدام بياناتك لأغراض تسويقية.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- الاحتفاظ بالبيانات --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">📁</span>
                    الاحتفاظ بالبيانات
                </h2>
                <div class="text-gray-700 space-y-3">
                    <p>نحتفظ ببياناتك طالما كان حسابك نشطاً أو حسب الحاجة لتقديم الخدمات.</p>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <ul class="space-y-2">
                            <li>• <strong>بيانات الحساب:</strong> طوال فترة نشاط الحساب + 3 سنوات بعد الإغلاق.</li>
                            <li>• <strong>نتائج الاختبارات:</strong> طوال فترة نشاط الحساب.</li>
                            <li>• <strong>سجلات المعاملات:</strong> 7 سنوات (متطلبات قانونية).</li>
                            <li>• <strong>سجلات الجلسات:</strong> 5 سنوات من تاريخ الجلسة.</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- خصوصية الأطفال --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">👶</span>
                    خصوصية الأطفال
                </h2>
                <p class="text-gray-700">
                    خدماتنا موجهة للأشخاص الذين تبلغ أعمارهم 18 عاماً أو أكثر. لا نجمع عن قصد بيانات من الأطفال دون سن 18 عاماً بدون موافقة ولي الأمر. إذا علمنا بجمع بيانات طفل بدون موافقة، سنقوم بحذفها فوراً.
                </p>
            </section>

            {{-- التحديثات --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">🔄</span>
                    تحديثات السياسة
                </h2>
                <p class="text-gray-700">
                    قد نقوم بتحديث هذه السياسة من وقت لآخر. سنخطرك بأي تغييرات جوهرية عبر البريد الإلكتروني أو إشعار على المنصة. ننصح بمراجعة هذه الصفحة دورياً.
                </p>
            </section>

            {{-- التواصل --}}
            <section class="bg-brand-DEFAULT/5 rounded-xl p-6">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">📧</span>
                    تواصل معنا
                </h2>
                <p class="text-gray-700 mb-4">
                    لأي استفسارات حول سياسة الخصوصية أو لممارسة حقوقك، تواصل معنا:
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-envelope text-brand-gold"></i>
                        <span>cs@thebrightbath.com</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-globe text-brand-gold"></i>
                        <span>thebrightbath.com</span>
                    </div>
                </div>
            </section>
        </div>

        {{-- Back Button --}}
        <div class="text-center mt-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-brand-DEFAULT hover:text-brand-gold transition">
                <i class="fas fa-arrow-right"></i>
                العودة للصفحة السابقة
            </a>
        </div>
    </div>
</div>
@endsection





