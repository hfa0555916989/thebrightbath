@extends('layouts.public')

@php
    $title = 'نماذج التحليل الوظيفي والكفاءات';
    $description = 'استكشف مجموعة متنوعة من نماذج التحليل الوظيفي والكفاءات التفاعلية. أدوات عملية تساعدك في تقييم وتطوير مهاراتك المهنية.';
@endphp

@section('content')

{{-- Hero Section --}}
<section class="pt-32 pb-12 bg-gradient-to-br from-brand-dark via-brand-DEFAULT to-brand-dark relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-72 h-72 bg-brand-gold rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-20 w-96 h-96 bg-brand-orange rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <span class="inline-block bg-brand-gold/20 backdrop-blur-sm text-brand-gold font-bold tracking-widest mb-6 uppercase text-sm px-6 py-2 rounded-full border border-brand-gold/30">
                <i class="fas fa-table ml-2"></i>
                أدوات التحليل المهني
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-white mb-6 leading-tight">
                نماذج التحليل الوظيفي
                <span class="text-brand-gold">والكفاءات</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                أدوات تفاعلية متخصصة تساعدك في تحليل وتقييم كفاءاتك المهنية وتطوير مسارك الوظيفي
            </p>
        </div>
    </div>
</section>

{{-- All Models Interactive Section --}}
@foreach($models as $model)
@php
    $sheets = $model->data['sheets'] ?? [];
    $modelColor = $model->color ?? '#D4AF37';
@endphp

@if(count($sheets) > 0)
<section class="py-8 bg-brand-bg" x-data="analysisForm{{ $model->id }}()">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            
            {{-- Model Header --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color: {{ $modelColor }}20;">
                    <i class="fas {{ $model->icon }} text-2xl" style="color: {{ $modelColor }};"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-brand-dark">{{ $model->name }}</h2>
                    @if($model->description)
                    <p class="text-brand-textMuted">{{ $model->description }}</p>
                    @endif
                </div>
            </div>
            
            {{-- Sheet Tabs --}}
            @if(count($sheets) > 1)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-6 overflow-x-auto">
                <div class="flex gap-2 min-w-max">
                    @foreach($sheets as $sheetIndex => $sheet)
                    <button @click="activeSheet = {{ $sheetIndex }}"
                            :class="activeSheet === {{ $sheetIndex }} ? 'bg-brand-gold text-brand-dark' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-5 py-3 rounded-xl font-medium transition whitespace-nowrap">
                        <i class="fas fa-file-alt ml-2"></i>
                        {{ $sheet['title'] }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Each Sheet Form --}}
            @foreach($sheets as $sheetIndex => $sheet)
            <div x-show="activeSheet === {{ $sheetIndex }}" 
                 x-transition
                 class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                
                {{-- Form Header --}}
                <div class="p-6 text-center border-b-4" style="border-color: {{ $modelColor }}; background: linear-gradient(135deg, {{ $modelColor }}15, {{ $modelColor }}05);">
                    <h3 class="text-2xl font-bold" style="color: {{ $modelColor }};">{{ $sheet['title'] }}</h3>
                </div>

                {{-- Actions Bar --}}
                <div class="p-4 bg-gray-50 border-b border-gray-200 print-hide">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle" style="color: {{ $modelColor }};"></i>
                            <span>أكمل الحقول ثم قم بالطباعة</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click="formData = {}" 
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-undo"></i>
                                إعادة تعيين
                            </button>
                            <button @click="window.print()" 
                                    class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-xl hover:opacity-90 transition"
                                    style="background-color: {{ $modelColor }};">
                                <i class="fas fa-print"></i>
                                طباعة
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8 space-y-8">
                    @foreach($sheet['sections'] as $sectionIndex => $section)
                    <div class="form-section pb-6 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        {{-- Section Header --}}
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $modelColor }}20;">
                                @php
                                    $icons = ['fa-info-circle', 'fa-tasks', 'fa-star', 'fa-graduation-cap', 'fa-chart-bar', 'fa-clipboard-list', 'fa-cogs', 'fa-lightbulb'];
                                    $icon = $icons[$sectionIndex % count($icons)];
                                @endphp
                                <i class="fas {{ $icon }}" style="color: {{ $modelColor }};"></i>
                            </div>
                            <h4 class="text-xl font-bold text-brand-dark">{{ $section['title'] }}</h4>
                        </div>
                        
                        @if($section['type'] === 'table' && !empty($section['table_headers']))
                        {{-- Table Section --}}
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-sm">
                                <thead>
                                    <tr class="border-b-2" style="border-color: {{ $modelColor }};">
                                        @foreach($section['table_headers'] as $header)
                                        <th class="px-3 py-3 text-right font-bold text-sm" style="background-color: {{ $modelColor }}15; color: {{ $modelColor }};">
                                            {{ $header }}
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($section['fields'] as $fieldIndex => $field)
                                    @if($field['type'] === 'table_row')
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        @foreach($field['cells'] as $cellIndex => $cell)
                                        @if($cellIndex < count($section['table_headers']))
                                        <td class="px-2 py-2">
                                            @if($cellIndex === 0 && !empty($cell))
                                                <span class="font-medium text-gray-700 text-sm">{{ $cell }}</span>
                                            @else
                                                @php
                                                    $cellValue = $cell ?? '';
                                                    $hasOptions = preg_match('/\[(.+)\]/', $cellValue, $matches);
                                                    $fieldKey = 'f_' . $sheetIndex . '_' . $sectionIndex . '_' . $fieldIndex . '_' . $cellIndex;
                                                @endphp
                                                @if($hasOptions)
                                                    @php $options = preg_split('/[\/|]/', $matches[1]); @endphp
                                                    <select class="w-full px-2 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold"
                                                            x-model="formData.{{ $fieldKey }}">
                                                        <option value="">اختر...</option>
                                                        @foreach($options as $opt)
                                                        <option value="{{ trim($opt) }}">{{ trim($opt) }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="text" 
                                                           class="w-full px-2 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold"
                                                           x-model="formData.{{ $fieldKey }}"
                                                           placeholder="{{ $cellValue ?: '...' }}">
                                                @endif
                                            @endif
                                        </td>
                                        @endif
                                        @endforeach
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @else
                        {{-- Regular Fields Section --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($section['fields'] as $fieldIndex => $field)
                            @if(!empty($field['label']) && $field['label'] !== 'البيان')
                            @php
                                $hasMultipleColumns = !empty($field['columns']) && count($field['columns']) > 0;
                                $isLargeField = str_contains($field['label'], 'ملاحظات') || str_contains($field['label'], 'وصف') || str_contains($field['label'], 'خطة') || str_contains($field['label'], 'هدف') || str_contains($field['label'], 'مسؤولية') || str_contains($field['label'], 'مهمة') || str_contains($field['label'], 'نقاط') || str_contains($field['label'], 'مجالات');
                                $colSpan = ($hasMultipleColumns || $isLargeField) ? 'md:col-span-2' : '';
                                $fieldKey = 'f_' . $sheetIndex . '_' . $sectionIndex . '_' . $fieldIndex;
                            @endphp
                            
                            @if($hasMultipleColumns)
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-{{ min(count($field['columns']) + 1, 4) }} gap-3 p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $field['label'] }}</label>
                                    <input type="text" 
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold"
                                           x-model="formData.{{ $fieldKey }}_0"
                                           placeholder="أدخل {{ $field['label'] }}">
                                </div>
                                @foreach($field['columns'] as $colIndex => $column)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $column['label'] }}</label>
                                    <input type="text" 
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold"
                                           x-model="formData.{{ $fieldKey }}_{{ $colIndex + 1 }}"
                                           placeholder="أدخل {{ $column['label'] }}">
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="{{ $colSpan }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $field['label'] }}</label>
                                
                                @if(!empty($field['options']))
                                <select class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold"
                                        x-model="formData.{{ $fieldKey }}">
                                    <option value="">اختر...</option>
                                    @foreach($field['options'] as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @elseif($isLargeField)
                                <textarea rows="2"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold resize-none"
                                          x-model="formData.{{ $fieldKey }}"
                                          placeholder="{{ $field['placeholder'] ?: 'أدخل ' . $field['label'] . '...' }}"></textarea>
                                @elseif(str_contains($field['label'], 'تاريخ'))
                                <input type="date" 
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold"
                                       x-model="formData.{{ $fieldKey }}">
                                @else
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:outline-none transition focus:border-brand-gold"
                                       x-model="formData.{{ $fieldKey }}"
                                       placeholder="{{ $field['placeholder'] ?: 'أدخل ' . $field['label'] }}">
                                @endif
                            </div>
                            @endif
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Form Footer --}}
                <div class="p-4 bg-gray-50 border-t border-gray-200 text-center print-hide">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-shield-alt ml-1" style="color: {{ $modelColor }};"></i>
                        بياناتك آمنة ولا يتم حفظها على الخادم
                    </p>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>
</section>

<script>
function analysisForm{{ $model->id }}() {
    return {
        activeSheet: 0,
        formData: {}
    }
}
</script>
@endif
@endforeach

{{-- Empty State --}}
@if($models->count() === 0)
<section class="py-20 bg-brand-bg">
    <div class="container mx-auto px-6">
        <div class="text-center py-16 bg-white rounded-2xl max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-folder-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد نماذج متاحة حالياً</h3>
            <p class="text-gray-500">سيتم إضافة نماذج جديدة قريباً</p>
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        <div class="bg-gradient-to-l from-brand-DEFAULT to-brand-dark rounded-3xl p-8 md:p-12 text-center relative overflow-hidden max-w-4xl mx-auto">
            <div class="absolute inset-0 opacity-10">
                <i class="fas fa-chart-line absolute -top-10 -right-10 text-[200px] transform rotate-12 text-white"></i>
            </div>
            <div class="relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                    هل تحتاج نموذج مخصص؟
                </h2>
                <p class="text-gray-300 mb-8">
                    تواصل معنا لتصميم نموذج تحليل مخصص يناسب احتياجاتك المهنية
                </p>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-8 py-4 rounded-xl font-bold hover:bg-white transition shadow-lg">
                    <i class="fas fa-envelope"></i>
                    تواصل معنا
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    @media print {
        .print-hide {
            display: none !important;
        }
        nav, footer, section:first-of-type {
            display: none !important;
        }
    }
</style>
@endpush
