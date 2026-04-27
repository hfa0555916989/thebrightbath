@extends('layouts.admin')

@section('title', 'تعديل الفصل')
@section('header', 'تعديل الفصل')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.book-chapters.index') }}" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right text-gray-600"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">تعديل الفصل</h2>
            <p class="text-brand-textMuted mt-1">تعديل محتوى وإعدادات الفصل</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="#" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">معلومات الفصل</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">عنوان الفصل <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required
                                   class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20"
                                   placeholder="عنوان الفصل">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الوصف المختصر</label>
                            <textarea name="description" rows="3"
                                      class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none"
                                      placeholder="وصف مختصر لمحتوى الفصل..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">محتوى الفصل <span class="text-red-500">*</span></label>
                            <textarea name="content" rows="15" required
                                      class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20"
                                      placeholder="محتوى الفصل..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">إعدادات النشر</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">الحالة</label>
                            <select name="status" class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                                <option value="draft">مسودة</option>
                                <option value="published">منشور</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-dark mb-2">ترتيب الفصل</label>
                            <input type="number" name="order" value="1" min="1"
                                   class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                        </div>

                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_free" class="w-5 h-5 rounded text-brand-gold focus:ring-brand-gold/20">
                                <span class="text-sm text-brand-dark">فصل مجاني</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
                    <h3 class="text-lg font-bold text-brand-dark mb-6">صورة الغلاف</h3>
                    
                    <div class="border-2 border-dashed border-brand-border rounded-lg p-6 text-center">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-brand-textMuted mb-2">اسحب الصورة هنا أو</p>
                        <label class="inline-block px-4 py-2 bg-brand-light text-brand-dark rounded-lg cursor-pointer hover:bg-gray-200 transition">
                            <span>اختر صورة</span>
                            <input type="file" name="cover" accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-brand-gold text-brand-dark py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التعديلات
                    </button>
                </div>

                <button type="button" class="w-full py-3 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition">
                    <i class="fas fa-trash ml-2"></i>
                    حذف الفصل
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

