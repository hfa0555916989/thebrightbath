@extends('layouts.public')

@php
    $title = 'تواصل معنا - الطريق المشرق للتدريب والتطوير';
    $description = 'تواصل مع الطريق المشرق للتدريب والتطوير للاستفسار عن خدماتنا واختباراتنا المهنية';
    $keywords = 'تواصل معنا، الطريق المشرق، استفسارات، حجز جلسة';
@endphp

@push('analytics')
<script>
    // تتبع صفحة التواصل
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'صفحة التواصل',
                'page_location': window.location.href,
                'custom_page_name': 'صفحة تواصل معنا'
            });
        }
    });
</script>
@endpush

@section('hide_newsletter')
@endsection

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-brand-DEFAULT via-brand-dark to-brand-navy overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <pattern id="hero-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="white"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#hero-pattern)"/>
            </svg>
        </div>
        
        {{-- Breadcrumb --}}
        <div class="container mx-auto px-6 relative z-10 mb-8">
            <nav class="text-sm text-gray-300">
                <a href="{{ route('home') }}" class="hover:text-brand-gold transition">الرئيسية</a>
                <span class="mx-2">/</span>
                <span class="text-brand-gold">تواصل معنا</span>
            </nav>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="inline-block text-brand-gold font-bold tracking-widest mb-4 uppercase text-sm">
                <i class="fas fa-envelope ml-2"></i>
                نحن هنا لمساعدتك
            </span>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-black mb-6">
                تواصل معنا
            </h1>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">
                نسعد بتلقي استفساراتكم والرد عليها في أقرب وقت ممكن
            </p>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                {{-- Contact Info --}}
                <div class="lg:col-span-1">
                    <h2 class="text-2xl font-display font-bold text-brand-dark mb-8">معلومات التواصل</h2>
                    
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                            <div class="w-14 h-14 bg-brand-gold/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-2xl text-brand-gold"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark mb-2">العنوان</h3>
                                <p class="text-brand-textMuted">المملكة العربية السعودية</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                            <div class="w-14 h-14 bg-brand-DEFAULT/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone-alt text-2xl text-brand-DEFAULT"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark mb-2">الهاتف</h3>
                                <a href="tel:+966543494316" class="text-brand-textMuted hover:text-brand-gold transition" dir="ltr">+966 54 349 4316</a>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                            <div class="w-14 h-14 bg-green-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-whatsapp text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark mb-2">واتساب</h3>
                                <a href="https://wa.me/966543494316" target="_blank" class="text-brand-textMuted hover:text-green-600 transition" dir="ltr">+966 54 349 4316</a>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                            <div class="w-14 h-14 bg-brand-orange/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-2xl text-brand-orange"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark mb-2">البريد الإلكتروني</h3>
                                <a href="mailto:cs@thebrightbath.com" class="text-brand-textMuted hover:text-brand-gold transition">cs@thebrightbath.com</a>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                            <div class="w-14 h-14 bg-green-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark mb-2">ساعات العمل</h3>
                                <p class="text-brand-textMuted">الأحد - الخميس: 9 ص - 5 م</p>
                            </div>
                        </div>
                    </div>

                    {{-- Social Links --}}
                    <div class="mt-8">
                        <h3 class="font-bold text-brand-dark mb-4">تابعنا على</h3>
                        <div class="flex gap-3">
                            <a href="#" class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-brand-textMuted hover:bg-brand-gold hover:text-white transition shadow-md">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-brand-textMuted hover:bg-brand-gold hover:text-white transition shadow-md">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-brand-textMuted hover:bg-brand-gold hover:text-white transition shadow-md">
                                <i class="fab fa-linkedin-in text-xl"></i>
                            </a>
                            <a href="https://wa.me/966543494316" target="_blank" class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-brand-textMuted hover:bg-green-500 hover:text-white transition shadow-md">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl p-8 shadow-xl">
                        <h2 class="text-2xl font-display font-bold text-brand-dark mb-2">أرسل لنا رسالة</h2>
                        <p class="text-brand-textMuted mb-8">املأ النموذج التالي وسنتواصل معك في أقرب وقت</p>

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
                                <i class="fas fa-check-circle text-xl"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <form id="whatsappContactForm" class="space-y-6" x-data="contactForm()">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-brand-dark font-medium mb-2">
                                        الاسم الكامل <span class="text-brand-red">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" required x-model="form.name"
                                           class="w-full px-4 py-3 rounded-xl border border-brand-border focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                           placeholder="أدخل اسمك الكامل">
                                </div>

                                <div>
                                    <label for="email" class="block text-brand-dark font-medium mb-2">
                                        البريد الإلكتروني <span class="text-brand-red">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" required x-model="form.email"
                                           value="{{ request('email') }}"
                                           class="w-full px-4 py-3 rounded-xl border border-brand-border focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                           placeholder="أدخل بريدك الإلكتروني">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-brand-dark font-medium mb-2">
                                        رقم الهاتف
                                    </label>
                                    <input type="tel" id="phone" name="phone" x-model="form.phone"
                                           class="w-full px-4 py-3 rounded-xl border border-brand-border focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                           placeholder="05XXXXXXXX" dir="ltr">
                                </div>

                                <div>
                                    <label for="subject" class="block text-brand-dark font-medium mb-2">
                                        الموضوع <span class="text-brand-red">*</span>
                                    </label>
                                    <select id="subject" name="subject" required x-model="form.subject"
                                            class="w-full px-4 py-3 rounded-xl border border-brand-border focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition">
                                        <option value="">اختر الموضوع</option>
                                        <option value="استفسار عام">استفسار عام</option>
                                        <option value="حجز جلسة إرشادية">حجز جلسة إرشادية</option>
                                        <option value="استفسار عن الاختبارات">استفسار عن الاختبارات</option>
                                        <option value="استفسار عن التدريب">استفسار عن التدريب</option>
                                        <option value="شراكة أو تعاون">شراكة أو تعاون</option>
                                        <option value="أخرى">أخرى</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="message" class="block text-brand-dark font-medium mb-2">
                                    الرسالة <span class="text-brand-red">*</span>
                                </label>
                                <textarea id="message" name="message" rows="5" required x-model="form.message"
                                          class="w-full px-4 py-3 rounded-xl border border-brand-border focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition resize-none"
                                          placeholder="اكتب رسالتك هنا..."></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <button type="button" @click="sendToWhatsApp()"
                                        class="inline-flex items-center justify-center gap-3 bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-xl font-bold hover:from-green-600 hover:to-green-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fab fa-whatsapp text-2xl"></i>
                                    <span>إرسال عبر الواتساب</span>
                                </button>
                                <span class="text-brand-textMuted text-sm">
                                    <i class="fas fa-shield-alt ml-1 text-green-500"></i>
                                    بياناتك محمية ولن نشاركها مع أحد
                                </span>
                            </div>

                            {{-- Success Message --}}
                            <div x-show="sent" x-transition class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3">
                                <i class="fas fa-check-circle text-xl"></i>
                                <span>تم فتح تطبيق الواتساب، يرجى إرسال الرسالة لإكمال التواصل</span>
                            </div>
                        </form>

                        <script>
                            function contactForm() {
                                return {
                                    form: {
                                        name: '',
                                        email: '{{ request("email") }}',
                                        phone: '',
                                        subject: '',
                                        message: ''
                                    },
                                    sent: false,
                                    sendToWhatsApp() {
                                        // Validate required fields
                                        if (!this.form.name || !this.form.email || !this.form.subject || !this.form.message) {
                                            alert('يرجى ملء جميع الحقول المطلوبة');
                                            return;
                                        }

                                        // Build WhatsApp message
                                        let message = `*رسالة جديدة من موقع الطريق المشرق*\n\n`;
                                        message += `📌 *الموضوع:* ${this.form.subject}\n\n`;
                                        message += `👤 *الاسم:* ${this.form.name}\n`;
                                        message += `📧 *البريد:* ${this.form.email}\n`;
                                        if (this.form.phone) {
                                            message += `📱 *الهاتف:* ${this.form.phone}\n`;
                                        }
                                        message += `\n💬 *الرسالة:*\n${this.form.message}`;

                                        // Encode and open WhatsApp
                                        const encodedMessage = encodeURIComponent(message);
                                        const whatsappUrl = `https://wa.me/966543494316?text=${encodedMessage}`;
                                        
                                        window.open(whatsappUrl, '_blank');
                                        this.sent = true;
                                    }
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Quick Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <span class="text-brand-gold font-bold text-sm tracking-wider uppercase">أسئلة سريعة</span>
                <h2 class="text-3xl font-display font-bold text-brand-dark mt-3">قبل أن تتواصل معنا</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="bg-brand-bg rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-brand-gold/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-question-circle text-2xl text-brand-gold"></i>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">كيف أبدأ الاختبار؟</h3>
                    <p class="text-brand-textMuted text-sm">اختر الاختبار المناسب من صفحة الاختبارات وابدأ مباشرة - بدون تسجيل!</p>
                </div>

                <div class="bg-brand-bg rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-brand-DEFAULT/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-2xl text-brand-DEFAULT"></i>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">كم يستغرق الرد؟</h3>
                    <p class="text-brand-textMuted text-sm">نرد على جميع الاستفسارات خلال 24-48 ساعة عمل</p>
                </div>

                <div class="bg-brand-bg rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-brand-orange/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-check text-2xl text-brand-orange"></i>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">كيف أحجز جلسة؟</h3>
                    <p class="text-brand-textMuted text-sm">أرسل لنا طلبك وسنتواصل معك لتحديد الموعد المناسب</p>
                </div>
            </div>
        </div>
    </section>

@endsection


