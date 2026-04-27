@extends('layouts.admin')

@section('title', 'إضافة نموذج تحليل جديد')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.analysis-models.index') }}" 
           class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-brand-dark">إضافة نموذج تحليل جديد</h1>
            <p class="text-brand-textMuted mt-1">ارفع ملف Excel لإنشاء نموذج تفاعلي</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.analysis-models.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        {{-- Basic Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-brand-gold"></i>
                المعلومات الأساسية
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        اسم النموذج <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition"
                           placeholder="مثال: نموذج تحليل الكفاءات القيادية">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        وصف النموذج
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition resize-none"
                              placeholder="وصف مختصر عن النموذج والغرض منه...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Icon --}}
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                        الأيقونة
                    </label>
                    <div class="relative">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-icons"></i>
                        </span>
                        <input type="text" name="icon" id="icon"
                               value="{{ old('icon', 'fa-file-alt') }}"
                               class="w-full pr-10 pl-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition"
                               placeholder="fa-file-alt">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        استخدم أيقونات Font Awesome 6 (مثال: fa-chart-bar, fa-users, fa-briefcase)
                    </p>
                </div>
                
                {{-- Color --}}
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        اللون
                    </label>
                    <div class="flex gap-2">
                        <input type="color" name="color" id="color"
                               value="{{ old('color', '#D4AF37') }}"
                               class="w-14 h-12 rounded-xl border border-gray-200 cursor-pointer">
                        <input type="text" id="colorText"
                               value="{{ old('color', '#D4AF37') }}"
                               class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition"
                               placeholder="#D4AF37">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Excel File --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-file-excel text-green-600"></i>
                ملف Excel
            </h2>
            
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-brand-gold transition"
                 x-data="{ dragover: false, fileName: '' }"
                 @dragover.prevent="dragover = true"
                 @dragleave.prevent="dragover = false"
                 @drop.prevent="dragover = false; $refs.fileInput.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0]?.name"
                 :class="{ 'border-brand-gold bg-brand-gold/5': dragover }">
                
                <input type="file" name="excel_file" id="excel_file" required
                       accept=".xlsx,.xls,.csv"
                       class="hidden"
                       x-ref="fileInput"
                       @change="fileName = $event.target.files[0]?.name">
                
                <label for="excel_file" class="cursor-pointer block">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-excel text-3xl text-green-600"></i>
                    </div>
                    <p class="text-gray-700 font-medium mb-2" x-show="!fileName">
                        اسحب وأفلت ملف Excel هنا
                    </p>
                    <p class="text-brand-gold font-bold mb-2" x-show="fileName" x-text="fileName"></p>
                    <p class="text-gray-500 text-sm" x-show="!fileName">
                        أو <span class="text-brand-gold font-medium">اضغط للاختيار</span>
                    </p>
                    <p class="text-gray-400 text-xs mt-2">
                        الصيغ المدعومة: xlsx, xls, csv (حد أقصى 10MB)
                    </p>
                </label>
            </div>
            @error('excel_file')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            <div class="mt-4 p-4 bg-blue-50 rounded-xl">
                <h3 class="font-medium text-blue-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-lightbulb"></i>
                    نصائح لأفضل نتيجة
                </h3>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>تأكد أن الصف الأول يحتوي على عناوين الأعمدة</li>
                    <li>استخدم أسماء واضحة ومفهومة للأعمدة</li>
                    <li>تجنب دمج الخلايا قدر الإمكان</li>
                    <li>تأكد من صحة البيانات الرقمية والتواريخ</li>
                </ul>
            </div>
        </div>
        
        {{-- Settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-cog text-brand-gold"></i>
                الإعدادات
            </h2>
            
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-5 h-5 rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    <div>
                        <span class="font-medium text-gray-900">تفعيل النموذج</span>
                        <p class="text-sm text-gray-500">سيكون النموذج متاحاً للعرض على الموقع</p>
                    </div>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1"
                           class="w-5 h-5 rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    <div>
                        <span class="font-medium text-gray-900">نموذج مميز</span>
                        <p class="text-sm text-gray-500">سيظهر في القسم المميز على الصفحة الرئيسية</p>
                    </div>
                </label>
            </div>
        </div>
        
        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.analysis-models.index') }}"
               class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition">
                إلغاء
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-brand-gold text-brand-dark rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg flex items-center gap-2">
                <i class="fas fa-save"></i>
                حفظ النموذج
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Sync color inputs
    document.getElementById('color').addEventListener('input', function() {
        document.getElementById('colorText').value = this.value;
    });
    document.getElementById('colorText').addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            document.getElementById('color').value = this.value;
        }
    });
</script>
@endpush
@endsection
