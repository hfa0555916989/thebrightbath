@extends('layouts.admin')

@section('title', 'الإعدادات')
@section('header', 'الإعدادات')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Page Header --}}
    <div>
        <h2 class="text-2xl font-bold text-brand-dark">إعدادات الموقع</h2>
        <p class="text-brand-textMuted mt-1">تخصيص إعدادات الموقع العامة</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        {{-- General Settings --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-cog text-brand-gold"></i>
                الإعدادات العامة
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">اسم الموقع</label>
                    <input type="text" name="site_name" value="الطريق المشرق"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">وصف الموقع</label>
                    <textarea name="site_description" rows="3"
                              class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">الطريق المشرق للتدريب والتطوير والإرشاد المهني</textarea>
                </div>
            </div>
        </div>

        {{-- Contact Settings --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-phone-alt text-brand-gold"></i>
                معلومات التواصل
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">البريد الإلكتروني</label>
                    <input type="email" name="contact_email" value="cs@thebrightbath.com"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">رقم الواتساب</label>
                    <input type="text" name="whatsapp_number" value="966543494316" dir="ltr"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">رقم الهاتف</label>
                    <input type="text" name="phone_number" value="+966543494316" dir="ltr"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">العنوان</label>
                    <input type="text" name="address" value="المملكة العربية السعودية"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
            </div>
        </div>

        {{-- Social Media --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-share-alt text-brand-gold"></i>
                روابط التواصل الاجتماعي
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">
                        <i class="fab fa-twitter text-blue-400 ml-2"></i>
                        تويتر (X)
                    </label>
                    <input type="url" name="twitter_url" placeholder="https://twitter.com/..."
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">
                        <i class="fab fa-instagram text-pink-500 ml-2"></i>
                        انستقرام
                    </label>
                    <input type="url" name="instagram_url" placeholder="https://instagram.com/..."
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">
                        <i class="fab fa-linkedin text-blue-600 ml-2"></i>
                        لينكدإن
                    </label>
                    <input type="url" name="linkedin_url" placeholder="https://linkedin.com/..."
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">
                        <i class="fab fa-youtube text-red-600 ml-2"></i>
                        يوتيوب
                    </label>
                    <input type="url" name="youtube_url" placeholder="https://youtube.com/..."
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
            </div>
        </div>

        {{-- WhatsApp Auto-Message --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fab fa-whatsapp text-green-500"></i>
                رسالة واتساب التلقائية للمستخدمين
            </h3>
            
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">نص الرسالة</label>
                <textarea name="whatsapp_auto_message" rows="4"
                          class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">السلام عليكم {name}،

شكراً لتسجيلك في منصة الطريق المشرق! 🌟

هل تحتاج مساعدة في اختيار الاختبار المناسب لك؟ أو لديك أي استفسار؟

نحن هنا لمساعدتك! 💪</textarea>
                <p class="text-xs text-brand-textMuted mt-2">استخدم {name} لإضافة اسم المستخدم تلقائياً</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                <i class="fas fa-save"></i>
                <span>حفظ الإعدادات</span>
            </button>
        </div>
    </form>
</div>
@endsection

