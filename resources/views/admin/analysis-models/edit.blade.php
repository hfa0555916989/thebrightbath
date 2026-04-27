@extends('layouts.admin')

@section('title', 'تعديل نموذج: ' . $model->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.analysis-models.index') }}" 
           class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-brand-dark">تعديل النموذج</h1>
            <p class="text-brand-textMuted mt-1">{{ $model->name }}</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.analysis-models.update', $model) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
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
                           value="{{ old('name', $model->name) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition">
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
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition resize-none">{{ old('description', $model->description) }}</textarea>
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
                            <i class="fas {{ $model->icon }}"></i>
                        </span>
                        <input type="text" name="icon" id="icon"
                               value="{{ old('icon', $model->icon) }}"
                               class="w-full pr-10 pl-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition">
                    </div>
                </div>
                
                {{-- Color --}}
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        اللون
                    </label>
                    <div class="flex gap-2">
                        <input type="color" name="color" id="color"
                               value="{{ old('color', $model->color) }}"
                               class="w-14 h-12 rounded-xl border border-gray-200 cursor-pointer">
                        <input type="text" id="colorText"
                               value="{{ old('color', $model->color) }}"
                               class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-brand-gold transition">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Current File Info --}}
        @if($model->file_path)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-file-excel text-green-600"></i>
                الملف الحالي
            </h2>
            <div class="flex items-center gap-4 p-4 bg-green-50 rounded-xl">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-excel text-2xl text-green-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ $model->original_file_name }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $model->structure['column_count'] ?? 0 }} عمود
                        •
                        {{ $model->data['row_count'] ?? 0 }} صف
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        {{-- New Excel File --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-upload text-brand-gold"></i>
                تحديث ملف Excel
            </h2>
            
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-brand-gold transition"
                 x-data="{ dragover: false, fileName: '' }"
                 @dragover.prevent="dragover = true"
                 @dragleave.prevent="dragover = false"
                 @drop.prevent="dragover = false; $refs.fileInput.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0]?.name"
                 :class="{ 'border-brand-gold bg-brand-gold/5': dragover }">
                
                <input type="file" name="excel_file" id="excel_file"
                       accept=".xlsx,.xls,.csv"
                       class="hidden"
                       x-ref="fileInput"
                       @change="fileName = $event.target.files[0]?.name">
                
                <label for="excel_file" class="cursor-pointer block">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-700 font-medium mb-2" x-show="!fileName">
                        اختياري: ارفع ملف جديد لتحديث البيانات
                    </p>
                    <p class="text-brand-gold font-bold mb-2" x-show="fileName" x-text="fileName"></p>
                    <p class="text-gray-500 text-sm" x-show="!fileName">
                        <span class="text-brand-gold font-medium">اضغط للاختيار</span> أو اسحب وأفلت
                    </p>
                </label>
            </div>
            @error('excel_file')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-cog text-brand-gold"></i>
                الإعدادات
            </h2>
            
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $model->is_active ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    <div>
                        <span class="font-medium text-gray-900">تفعيل النموذج</span>
                        <p class="text-sm text-gray-500">سيكون النموذج متاحاً للعرض على الموقع</p>
                    </div>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ $model->is_featured ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    <div>
                        <span class="font-medium text-gray-900">نموذج مميز</span>
                        <p class="text-sm text-gray-500">سيظهر في القسم المميز على الصفحة الرئيسية</p>
                    </div>
                </label>
            </div>
        </div>
        
        {{-- Statistics --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-brand-gold"></i>
                الإحصائيات
            </h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($model->views_count) }}</div>
                    <div class="text-sm text-gray-500">مشاهدة</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($model->downloads_count) }}</div>
                    <div class="text-sm text-gray-500">تحميل</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-2xl font-bold text-gray-600">{{ $model->created_at->diffForHumans() }}</div>
                    <div class="text-sm text-gray-500">تاريخ الإنشاء</div>
                </div>
            </div>
        </div>
        
        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <form action="{{ route('admin.analysis-models.destroy', $model) }}" method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا النموذج نهائياً؟')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-6 py-3 border border-red-300 rounded-xl text-red-600 font-medium hover:bg-red-50 transition flex items-center gap-2">
                    <i class="fas fa-trash"></i>
                    حذف النموذج
                </button>
            </form>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.analysis-models.index') }}"
                   class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition">
                    إلغاء
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-brand-gold text-brand-dark rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
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
