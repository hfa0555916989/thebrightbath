@extends('layouts.public')

@php
    $title = $model->name . ' - نماذج التحليل الوظيفي';
    $description = $model->description ?? 'نموذج تفاعلي للتحليل الوظيفي والكفاءات من الطريق المشرق للتدريب والتطوير';
    $sheets = $model->data['sheets'] ?? [];
@endphp

@section('content')

{{-- Hero Section --}}
<section class="pt-32 pb-8 bg-gradient-to-br from-brand-dark via-brand-DEFAULT to-brand-dark relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-72 h-72 rounded-full blur-3xl" style="background-color: {{ $model->color }};"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-5xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a>
                <i class="fas fa-chevron-left text-xs"></i>
                <a href="{{ route('analysis-models.index') }}" class="hover:text-white transition">نماذج التحليل</a>
                <i class="fas fa-chevron-left text-xs"></i>
                <span class="text-brand-gold">{{ $model->name }}</span>
            </nav>
            
            <div class="flex items-start gap-6">
                <div class="hidden md:flex w-16 h-16 rounded-2xl items-center justify-center flex-shrink-0" 
                     style="background-color: {{ $model->color }}30;">
                    <i class="fas {{ $model->icon }} text-3xl" style="color: {{ $model->color }};"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">
                        {{ $model->name }}
                    </h1>
                    <p class="text-gray-300">
                        @if(count($sheets) > 1)
                            يحتوي على {{ count($sheets) }} نماذج - اختر النموذج المطلوب من الأسفل
                        @else
                            أكمل النموذج التالي وقم بتحميله أو طباعته
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Form Section --}}
<section class="py-8 bg-brand-bg" x-data="dynamicForm({{ json_encode($sheets) }})">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            
            {{-- Sheet Tabs (if multiple sheets) --}}
            @if(count($sheets) > 1)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-6 overflow-x-auto">
                <div class="flex gap-2 min-w-max">
                    @foreach($sheets as $index => $sheet)
                    <button @click="activeSheet = {{ $index }}"
                            :class="activeSheet === {{ $index }} ? 'bg-brand-gold text-brand-dark' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-3 rounded-xl font-medium transition whitespace-nowrap">
                        <i class="fas fa-file-alt ml-2"></i>
                        {{ $sheet['title'] }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Actions Bar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 sticky top-20 z-30 print:hidden">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle text-brand-gold"></i>
                        <span>أكمل الحقول ثم قم بالطباعة أو التحميل</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="resetForm()" 
                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                            <i class="fas fa-undo"></i>
                            <span class="hidden sm:inline">إعادة تعيين</span>
                        </button>
                        <button @click="printForm()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-brand-DEFAULT text-white rounded-xl hover:bg-brand-dark transition">
                            <i class="fas fa-print"></i>
                            <span class="hidden sm:inline">طباعة</span>
                        </button>
                        <button @click="downloadPDF()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-brand-gold text-brand-dark rounded-xl font-medium hover:bg-brand-goldDeep transition">
                            <i class="fas fa-download"></i>
                            <span class="hidden sm:inline">تحميل PDF</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Dynamic Form for Each Sheet --}}
            @foreach($sheets as $sheetIndex => $sheet)
            <div x-show="activeSheet === {{ $sheetIndex }}" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 id="printableForm-{{ $sheetIndex }}"
                 class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden printable-form">
                
                {{-- Form Header --}}
                <div class="p-6 text-center border-b-4" style="border-color: {{ $model->color }}; background: linear-gradient(135deg, {{ $model->color }}15, {{ $model->color }}05);">
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <img src="{{ asset('images/bright-path-logo.png') }}" alt="الطريق المشرق" class="h-16 print:h-12">
                    </div>
                    <h2 class="text-2xl font-bold" style="color: {{ $model->color }};">{{ $sheet['title'] }}</h2>
                </div>

                <div class="p-6 md:p-8 space-y-8">
                    @foreach($sheet['sections'] as $sectionIndex => $section)
                    <div class="form-section">
                        {{-- Section Header --}}
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $model->color }}20;">
                                @php
                                    $icons = ['fa-info-circle', 'fa-tasks', 'fa-star', 'fa-graduation-cap', 'fa-chart-bar', 'fa-clipboard-list', 'fa-cogs', 'fa-lightbulb'];
                                    $icon = $icons[$sectionIndex % count($icons)];
                                @endphp
                                <i class="fas {{ $icon }}" style="color: {{ $model->color }};"></i>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark">{{ $section['title'] }}</h3>
                        </div>
                        
                        @if($section['type'] === 'table' && !empty($section['table_headers']))
                        {{-- Table Section --}}
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="border-b-2" style="border-color: {{ $model->color }};">
                                        @foreach($section['table_headers'] as $header)
                                        <th class="px-4 py-3 text-right font-bold text-sm" style="background-color: {{ $model->color }}15; color: {{ $model->color }};">
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
                                                <span class="font-medium text-gray-700">{{ $cell }}</span>
                                            @else
                                                @php
                                                    $cellValue = $cell ?? '';
                                                    $hasOptions = preg_match('/\[(.+)\]/', $cellValue, $matches);
                                                @endphp
                                                @if($hasOptions)
                                                    @php $options = preg_split('/[\/|]/', $matches[1]); @endphp
                                                    <select class="form-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:border-transparent transition"
                                                            x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}_{{ $cellIndex }}']">
                                                        <option value="">اختر...</option>
                                                        @foreach($options as $opt)
                                                        <option value="{{ trim($opt) }}">{{ trim($opt) }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="text" 
                                                           class="form-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:border-transparent transition"
                                                           x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}_{{ $cellIndex }}']"
                                                           placeholder="{{ $cellValue ?: 'أدخل البيانات...' }}">
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
                                $colSpan = $hasMultipleColumns ? '' : (str_contains($field['label'], 'ملاحظات') || str_contains($field['label'], 'وصف') || str_contains($field['label'], 'خطة') ? 'md:col-span-2' : '');
                            @endphp
                            
                            @if($hasMultipleColumns)
                            {{-- Multi-column field --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-{{ count($field['columns']) + 1 }} gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="field-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $field['label'] }}</label>
                                    @if(!empty($field['options']))
                                    <select class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition"
                                            x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}_0']">
                                        <option value="">اختر...</option>
                                        @foreach($field['options'] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="{{ $field['type'] === 'date' ? 'date' : ($field['type'] === 'number' ? 'number' : 'text') }}" 
                                           class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition"
                                           x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}_0']"
                                           placeholder="أدخل {{ $field['label'] }}">
                                    @endif
                                </div>
                                @foreach($field['columns'] as $colIndex => $column)
                                <div class="field-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $column['label'] }}</label>
                                    <input type="text" 
                                           class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition"
                                           x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}_{{ $colIndex + 1 }}']"
                                           placeholder="أدخل {{ $column['label'] }}">
                                </div>
                                @endforeach
                            </div>
                            @else
                            {{-- Single field --}}
                            <div class="field-group {{ $colSpan }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $field['label'] }}</label>
                                
                                @if(!empty($field['options']))
                                <select class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition"
                                        x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}']">
                                    <option value="">اختر...</option>
                                    @foreach($field['options'] as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @elseif($field['type'] === 'textarea' || str_contains($field['label'], 'ملاحظات') || str_contains($field['label'], 'وصف') || str_contains($field['label'], 'خطة') || str_contains($field['label'], 'هدف') || str_contains($field['label'], 'مسؤولية') || str_contains($field['label'], 'مهمة') || str_contains($field['label'], 'نقاط') || str_contains($field['label'], 'مجالات'))
                                <textarea rows="3"
                                          class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition resize-none"
                                          x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}']"
                                          placeholder="{{ $field['placeholder'] ?: 'أدخل ' . $field['label'] . '...' }}"></textarea>
                                @elseif($field['type'] === 'date' || str_contains($field['label'], 'تاريخ'))
                                <input type="date" 
                                       class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition"
                                       x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}']">
                                @elseif($field['type'] === 'number' || str_contains($field['label'], 'عدد') || str_contains($field['label'], 'سنوات') || str_contains($field['label'], 'راتب') || str_contains($field['label'], 'نسبة'))
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition"
                                       x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}']"
                                       placeholder="{{ $field['placeholder'] ?: 'أدخل ' . $field['label'] }}">
                                @else
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:border-transparent transition"
                                       x-model="formData['{{ $sheetIndex }}_{{ $sectionIndex }}_{{ $fieldIndex }}']"
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
                <div class="p-6 bg-gray-50 border-t border-gray-200 print:hidden">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-shield-alt text-brand-gold ml-1"></i>
                            بياناتك آمنة ولا يتم حفظها على الخادم
                        </p>
                        <div class="flex items-center gap-3">
                            <button @click="printForm()" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-brand-DEFAULT text-white rounded-xl hover:bg-brand-dark transition">
                                <i class="fas fa-print"></i>
                                طباعة النموذج
                            </button>
                            <button @click="downloadPDF()" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-brand-gold text-brand-dark rounded-xl font-bold hover:bg-brand-goldDeep transition">
                                <i class="fas fa-file-pdf"></i>
                                تحميل PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Back Link --}}
            <div class="mt-8 text-center print:hidden">
                <a href="{{ route('analysis-models.index') }}" 
                   class="inline-flex items-center gap-2 text-brand-DEFAULT hover:text-brand-dark font-medium transition">
                    <i class="fas fa-arrow-right"></i>
                    العودة لجميع النماذج
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .form-input:focus {
        --tw-ring-color: {{ $model->color }};
        box-shadow: 0 0 0 3px {{ $model->color }}30;
    }
    
    .form-section {
        padding-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .form-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        nav, footer, .print\\:hidden {
            display: none !important;
        }
        
        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }
        
        .printable-form {
            box-shadow: none !important;
            border: none !important;
        }
        
        .form-input, select, textarea {
            border: 1px solid #ccc !important;
            background: white !important;
        }
        
        [x-show]:not(.printable-form) {
            display: none !important;
        }
        
        .printable-form[x-show="true"], 
        .printable-form:not([style*="display: none"]) {
            display: block !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function dynamicForm(sheets) {
    return {
        sheets: sheets,
        activeSheet: 0,
        formData: {},
        
        resetForm() {
            if (confirm('هل أنت متأكد من إعادة تعيين جميع الحقول؟')) {
                this.formData = {};
            }
        },
        
        printForm() {
            window.print();
        },
        
        downloadPDF() {
            const element = document.getElementById('printableForm-' + this.activeSheet);
            const sheetTitle = this.sheets[this.activeSheet]?.title || 'نموذج';
            
            const opt = {
                margin: 10,
                filename: sheetTitle + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true,
                    logging: false
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait'
                }
            };
            
            // Show loading
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري التحميل...';
            btn.disabled = true;
            
            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    }
}
</script>
@endpush
