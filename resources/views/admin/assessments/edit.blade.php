@extends('layouts.admin')

@section('title', 'تعديل الاختبار')
@section('header', 'تعديل الاختبار')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.assessments.index') }}" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right text-gray-600"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">تعديل: {{ $assessment->name }}</h2>
            <p class="text-brand-textMuted mt-1">تعديل بيانات الاختبار والصورة</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
        <i class="fas fa-check-circle ml-2"></i>{{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.assessments.update', $assessment) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">معلومات الاختبار</h3>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">اسم الاختبار <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $assessment->name) }}" required
                                   class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الرابط (Slug)</label>
                            <input type="text" name="slug" value="{{ old('slug', $assessment->slug) }}"
                                   class="w-full px-4 py-3 border border-brand-border rounded-lg font-mono text-sm focus:ring-2 focus:ring-brand-primary focus:border-transparent" dir="ltr">
                            @error('slug') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">النوع</label>
                            <select name="type" class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                <option value="">-- اختر النوع --</option>
                                <option value="interests"  {{ old('type', $assessment->type) == 'interests'  ? 'selected' : '' }}>ميول مهنية</option>
                                <option value="personality"{{ old('type', $assessment->type) == 'personality'? 'selected' : '' }}>تحليل شخصية</option>
                                <option value="intelligence"{{ old('type', $assessment->type) == 'intelligence'? 'selected' : '' }}>ذكاءات</option>
                                <option value="values"     {{ old('type', $assessment->type) == 'values'     ? 'selected' : '' }}>قيم مهنية</option>
                                <option value="career"     {{ old('type', $assessment->type) == 'career'     ? 'selected' : '' }}>توافق مهني</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الوصف</label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-3 border border-brand-border rounded-lg resize-none focus:ring-2 focus:ring-brand-primary focus:border-transparent">{{ old('description', $assessment->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Image Upload --}}
                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">صورة الاختبار</h3>

                    @if($assessment->image)
                    <div class="mb-4">
                        <p class="text-sm text-brand-textMuted mb-2">الصورة الحالية:</p>
                        <img src="{{ Storage::url($assessment->image) }}" alt="{{ $assessment->name }}"
                             class="w-48 h-32 object-cover rounded-lg border border-brand-border">
                    </div>
                    @endif

                    <div class="border-2 border-dashed border-brand-border rounded-xl p-6 text-center">
                        <input type="file" name="image" id="image-upload" accept="image/*" class="hidden"
                               onchange="previewImage(this)">
                        <label for="image-upload" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-brand-textMuted mb-3 block"></i>
                            <p class="text-brand-dark font-medium">اضغط لرفع صورة</p>
                            <p class="text-brand-textMuted text-sm mt-1">PNG, JPG حتى 2MB</p>
                        </label>
                        <img id="image-preview" src="" alt="" class="hidden mt-4 mx-auto w-48 h-32 object-cover rounded-lg">
                    </div>
                    @error('image') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">الإعدادات</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الحالة</label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ $assessment->is_active ? 'checked' : '' }}
                                       class="w-5 h-5 rounded text-brand-primary">
                                <span class="text-brand-dark">نشط</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الوقت المقدر (دقائق)</label>
                            <input type="number" name="estimated_minutes" min="1" max="120"
                                   value="{{ old('estimated_minutes', $assessment->estimated_minutes) }}"
                                   class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الأيقونة (Font Awesome)</label>
                            <input type="text" name="icon" value="{{ old('icon', $assessment->icon) }}"
                                   placeholder="مثال: fa-brain"
                                   class="w-full px-4 py-2 border border-brand-border rounded-lg font-mono text-sm focus:ring-2 focus:ring-brand-primary focus:border-transparent" dir="ltr">
                            @if($assessment->icon)
                            <p class="text-sm text-brand-textMuted mt-1">
                                معاينة: <i class="fas {{ $assessment->icon }}"></i>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <button type="submit" class="w-full bg-brand-primary text-white py-3 rounded-lg font-bold hover:bg-brand-primaryDark transition">
                        <i class="fas fa-save ml-2"></i>حفظ التغييرات
                    </button>
                    <a href="{{ route('admin.assessments.questions', $assessment) }}"
                       class="block text-center py-3 border border-brand-border rounded-lg text-brand-dark hover:bg-gray-50 transition">
                        <i class="fas fa-list-ul ml-2"></i>إدارة الأسئلة
                    </a>
                    <a href="{{ route('admin.assessments.index') }}"
                       class="block text-center py-3 border border-brand-border rounded-lg text-brand-textMuted hover:bg-gray-50 transition">
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('image-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
