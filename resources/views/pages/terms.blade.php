@extends('layouts.public')

@section('title', 'الشروط والأحكام')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-brand-bg to-white py-12 px-4">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-brand-dark mb-4">الشروط والأحكام</h1>
            <p class="text-gray-500">آخر تحديث: 12 ديسمبر 2025</p>
        </div>

        {{-- Content Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-10">
            {{-- مقدمة --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">📋</span>
                    مقدمة
                </h2>
                <p class="text-gray-700 leading-relaxed">
                    مرحباً بك في منصة <strong>"الطريق المشرق للتدريب والتطوير"</strong>. باستخدامك لهذا الموقع أو خدماتنا، فإنك توافق على الالتزام بهذه الشروط والأحكام. يُرجى قراءتها بعناية قبل استخدام خدماتنا.
                </p>
            </section>

            {{-- تعريفات --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">📖</span>
                    التعريفات
                </h2>
                <ul class="space-y-2 text-gray-700">
                    <li><strong>"المنصة":</strong> موقع الطريق المشرق للتدريب والتطوير وجميع خدماته الإلكترونية.</li>
                    <li><strong>"المستخدم" أو "العميل":</strong> أي شخص يستخدم المنصة أو يستفيد من خدماتها.</li>
                    <li><strong>"المستشار":</strong> المختص المعتمد الذي يقدم الاستشارات عبر المنصة.</li>
                    <li><strong>"الخدمات":</strong> تشمل الاختبارات المهنية، الاستشارات الفورية، والمحتوى التعليمي.</li>
                </ul>
            </section>

            {{-- شروط الاستخدام --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">✅</span>
                    شروط الاستخدام
                </h2>
                <div class="space-y-4 text-gray-700">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2">1. الأهلية</h3>
                        <p>يجب أن يكون عمرك 18 عاماً أو أكثر لاستخدام خدماتنا. إذا كنت أقل من 18 عاماً، يجب الحصول على موافقة ولي الأمر.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2">2. التسجيل والحساب</h3>
                        <ul class="list-disc list-inside space-y-1 mr-4">
                            <li>يجب تقديم معلومات صحيحة ودقيقة عند التسجيل.</li>
                            <li>أنت مسؤول عن الحفاظ على سرية بيانات حسابك.</li>
                            <li>يحق للمنصة تعليق أو إلغاء الحسابات المخالفة.</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2">3. السلوك المقبول</h3>
                        <ul class="list-disc list-inside space-y-1 mr-4">
                            <li>استخدام المنصة للأغراض المشروعة فقط.</li>
                            <li>احترام خصوصية المستشارين والمستخدمين الآخرين.</li>
                            <li>عدم نشر محتوى مسيء أو غير لائق.</li>
                            <li>عدم محاولة اختراق أو تعطيل أنظمة المنصة.</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- الخدمات والأسعار --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">💰</span>
                    الخدمات والأسعار
                </h2>
                <div class="space-y-4 text-gray-700">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2">1. الاختبارات المهنية</h3>
                        <p>نقدم مجموعة من اختبارات تحليل الشخصية والميول المهنية. بعض الاختبارات مجانية والأخرى تتطلب اشتراكاً.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2">2. الاستشارات الفورية</h3>
                        <ul class="list-disc list-inside space-y-1 mr-4">
                            <li>الأسعار تُحدد من قبل كل مستشار حسب خبرته وتخصصه.</li>
                            <li>يتم الدفع بعد موافقة المستشار على طلب الحجز.</li>
                            <li>جميع الأسعار بالريال السعودي وتشمل ضريبة القيمة المضافة.</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="font-semibold mb-2">3. سياسة الإلغاء والاسترداد</h3>
                        <ul class="list-disc list-inside space-y-1 mr-4">
                            <li>يمكن إلغاء الحجز قبل ساعتين من موعد الجلسة مع استرداد كامل المبلغ.</li>
                            <li>الإلغاء بعد هذه المدة لا يستحق استرداداً.</li>
                            <li>في حالة عدم حضور المستشار، يُسترد المبلغ كاملاً.</li>
                            <li>يتم معالجة الاسترداد خلال 5-10 أيام عمل.</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- حقوق الملكية الفكرية --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">©️</span>
                    حقوق الملكية الفكرية
                </h2>
                <div class="text-gray-700 space-y-3">
                    <p>جميع المحتويات المنشورة على المنصة بما في ذلك النصوص والصور والشعارات والاختبارات هي ملك للطريق المشرق أو مرخصة لها.</p>
                    <p>يُحظر نسخ أو توزيع أو تعديل أي محتوى دون إذن كتابي مسبق.</p>
                    <p>نتائج الاختبارات مخصصة للاستخدام الشخصي فقط ولا يجوز استخدامها تجارياً.</p>
                </div>
            </section>

            {{-- إخلاء المسؤولية --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">⚠️</span>
                    إخلاء المسؤولية
                </h2>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-gray-700">
                    <ul class="space-y-2">
                        <li>• الاستشارات المقدمة هي للإرشاد والتوجيه ولا تُعد بديلاً عن الاستشارات الطبية أو النفسية المتخصصة.</li>
                        <li>• نتائج الاختبارات استرشادية وتساعد في فهم الميول والشخصية، ولا تُعتبر تشخيصاً علمياً نهائياً.</li>
                        <li>• المنصة غير مسؤولة عن القرارات المتخذة بناءً على الاستشارات أو نتائج الاختبارات.</li>
                        <li>• المستشارون مستقلون ومسؤولون عن محتوى استشاراتهم.</li>
                    </ul>
                </div>
            </section>

            {{-- التعديلات --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">🔄</span>
                    التعديلات على الشروط
                </h2>
                <p class="text-gray-700">
                    يحق للمنصة تعديل هذه الشروط في أي وقت. سيتم إشعار المستخدمين بالتغييرات الجوهرية عبر البريد الإلكتروني أو من خلال إشعار على المنصة. استمرارك في استخدام الخدمات يُعد موافقة على الشروط المحدّثة.
                </p>
            </section>

            {{-- القانون الحاكم --}}
            <section class="mb-8">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">⚖️</span>
                    القانون الحاكم
                </h2>
                <p class="text-gray-700">
                    تخضع هذه الشروط والأحكام لأنظمة المملكة العربية السعودية. أي نزاعات تنشأ عن استخدام المنصة تخضع للاختصاص القضائي للمحاكم السعودية.
                </p>
            </section>

            {{-- التواصل --}}
            <section class="bg-brand-DEFAULT/5 rounded-xl p-6">
                <h2 class="text-xl font-bold text-brand-DEFAULT mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-gold/20 rounded-lg flex items-center justify-center">📧</span>
                    تواصل معنا
                </h2>
                <p class="text-gray-700 mb-4">
                    لأي استفسارات حول هذه الشروط والأحكام، يُرجى التواصل معنا:
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





