<div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border space-y-5">

    {{-- Title --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-brand-dark mb-2">العنوان <span class="text-brand-red">*</span></label>
            <input type="text" name="title" value="{{ old('title', $item->title ?? '') }}" required
                class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
        </div>
        <div>
            <label class="block text-sm font-medium text-brand-dark mb-2">العنوان الفرعي</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $item->subtitle ?? '') }}"
                class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
            <p class="text-xs text-brand-textMuted mt-1">مثال: المنصب والمدينة للشهادات</p>
        </div>
    </div>

    {{-- Body --}}
    <div>
        <label class="block text-sm font-medium text-brand-dark mb-2">النص / الوصف</label>
        <textarea name="body" rows="4"
            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">{{ old('body', $item->body ?? '') }}</textarea>
    </div>

    {{-- Icon & Color --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-brand-dark mb-2">أيقونة Font Awesome</label>
            <input type="text" name="icon" value="{{ old('icon', $item->icon ?? '') }}" placeholder="fas fa-medal"
                class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 font-mono" dir="ltr">
            <p class="text-xs text-brand-textMuted mt-1">
                أمثلة: fas fa-medal, fas fa-users, fas fa-star
                <a href="https://fontawesome.com/icons" target="_blank" class="text-brand-gold hover:underline">استعرض الأيقونات</a>
            </p>
        </div>
        <div x-data="{ c: '{{ old('color', $item->color ?? '#F8C524') }}' }">
            <label class="block text-sm font-medium text-brand-dark mb-2">اللون</label>
            <div class="flex items-center gap-3">
                <input type="color" name="color" x-model="c"
                    class="w-12 h-12 rounded-lg border border-brand-border cursor-pointer">
                <input type="text" x-model="c" readonly
                    class="flex-1 px-3 py-3 border border-brand-border rounded-lg text-sm font-mono bg-gray-50">
                <div class="w-12 h-12 rounded-lg border border-brand-border" :style="`background:${c}`"></div>
            </div>
        </div>
    </div>

    {{-- Image --}}
    <div x-data="{ preview: '' }">
        <label class="block text-sm font-medium text-brand-dark mb-2">الصورة</label>
        <div class="flex items-start gap-4">
            <div class="w-36 h-24 bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-brand-border flex-shrink-0 flex items-center justify-center">
                @if(isset($item) && $item && $item->image)
                    <img :src="preview || '{{ $item->image_url }}'" class="w-full h-full object-cover">
                @else
                    <img :src="preview" class="w-full h-full object-cover" x-show="preview">
                    <i class="fas fa-image text-3xl text-gray-300" x-show="!preview"></i>
                @endif
            </div>
            <div class="flex-1 space-y-2">
                <input type="file" name="image" accept="image/*"
                    @change="preview = URL.createObjectURL($event.target.files[0])"
                    class="w-full px-4 py-3 border border-brand-border rounded-lg">
                <p class="text-xs text-brand-textMuted">أو أدخل رابط URL مباشرة:</p>
                <input type="url" name="image_url" value="{{ old('image_url', isset($item) && $item && str_starts_with($item->image ?? '', 'http') ? $item->image : '') }}"
                    placeholder="https://images.unsplash.com/..." dir="ltr"
                    class="w-full px-4 py-2 border border-brand-border rounded-lg text-sm">
            </div>
        </div>
    </div>

    {{-- Link --}}
    <div>
        <label class="block text-sm font-medium text-brand-dark mb-2">الرابط (اختياري)</label>
        <input type="text" name="link" value="{{ old('link', $item->link ?? '') }}" placeholder="assessments.index أو https://..."
            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20" dir="ltr">
    </div>

    {{-- Meta Fields (type-specific) --}}
    @if(in_array($type, ['testimonials']))
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl">
        <div>
            <label class="block text-sm font-medium text-brand-dark mb-2">التقييم (1-5)</label>
            <input type="number" name="meta_rating" value="{{ old('meta_rating', $item->getMeta('rating', 5) ?? 5) }}" min="1" max="5" step="0.5"
                class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
        </div>
        <div>
            <label class="block text-sm font-medium text-brand-dark mb-2">حرف الأفاتار</label>
            <input type="text" name="meta_avatar_letter" value="{{ old('meta_avatar_letter', $item->getMeta('avatar_letter') ?? '') }}" maxlength="2"
                class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
            <p class="text-xs text-brand-textMuted mt-1">الحرف الأول من اسم الشخص</p>
        </div>
    </div>
    @endif

    @if(in_array($type, ['goals']))
    <div class="p-4 bg-gray-50 rounded-xl">
        <label class="block text-sm font-medium text-brand-dark mb-2">الأهداف الفرعية (كل هدف في سطر جديد)</label>
        <textarea name="meta_sub_goals" rows="4"
            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">{{ old('meta_sub_goals', isset($item) && $item ? implode("\n", $item->getMeta('sub_goals', [])) : '') }}</textarea>
    </div>
    @endif

    @if(in_array($type, ['service-details']))
    <div class="p-4 bg-gray-50 rounded-xl">
        <label class="block text-sm font-medium text-brand-dark mb-2">بنود الخدمة (كل بند في سطر جديد)</label>
        <textarea name="meta_items" rows="5"
            class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none">{{ old('meta_items', isset($item) && $item ? implode("\n", $item->getMeta('items', [])) : '') }}</textarea>
        <div class="grid grid-cols-2 gap-3 mt-3">
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-1">نص الزر</label>
                <input type="text" name="meta_btn_text" value="{{ old('meta_btn_text', isset($item) && $item ? $item->getMeta('btn_text') : '') }}"
                    class="w-full px-3 py-2 border border-brand-border rounded-lg text-sm">
            </div>
        </div>
    </div>
    @endif

    {{-- Active Toggle --}}
    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}
            class="w-5 h-5 rounded border-brand-border text-brand-gold focus:ring-brand-gold">
        <label for="is_active" class="text-sm font-medium text-brand-dark cursor-pointer">
            نشط ويظهر في الموقع
        </label>
    </div>
</div>
