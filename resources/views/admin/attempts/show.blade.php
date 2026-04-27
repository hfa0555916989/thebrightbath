@extends('layouts.admin')

@section('title', 'تفاصيل نتيجة الاختبار #' . $attempt->id)

@section('content')
<div class="space-y-6">
    
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.attempts.index') }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-right"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">تفاصيل نتيجة الاختبار #{{ $attempt->id }}</h1>
            </div>
            <p class="text-gray-600">{{ $attempt->assessment_name }}</p>
        </div>
        <div class="flex gap-2">
            @if($attempt->client_phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $attempt->client_phone) }}?text=السلام عليكم {{ $attempt->client_name }}، بخصوص نتيجة اختبار {{ $attempt->assessment_name }}" 
               target="_blank"
               class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                <i class="fab fa-whatsapp"></i>
                <span>تواصل واتساب</span>
            </a>
            @endif
            @if($attempt->client_email)
            <a href="mailto:{{ $attempt->client_email }}?subject=نتيجة اختبار {{ $attempt->assessment_name }}" 
               class="inline-flex items-center gap-2 bg-brand-DEFAULT text-white px-4 py-2 rounded-lg hover:bg-brand-dark transition">
                <i class="fas fa-envelope"></i>
                <span>إرسال بريد</span>
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Result Card --}}
            <div class="bg-gradient-to-br from-brand-DEFAULT to-brand-dark rounded-2xl p-8 text-white">
                <div class="text-center">
                    <div class="text-sm uppercase tracking-wider text-brand-gold mb-2">النتيجة</div>
                    <div class="text-6xl font-bold mb-4">{{ $attempt->type_code }}</div>
                    @if($attempt->summary)
                    <p class="text-gray-300 max-w-xl mx-auto">{{ $attempt->summary }}</p>
                    @endif
                </div>
            </div>

            {{-- Scores --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-brand-gold"></i>
                    تفاصيل الدرجات
                </h3>
                
                @if($attempt->scores_json)
                <div class="space-y-4">
                    @php
                        $scores = $attempt->scores_json;
                        $maxScore = max($scores) ?: 1;
                    @endphp
                    @foreach($scores as $dimension => $score)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-700 font-medium">{{ $dimension }}</span>
                            <span class="text-brand-DEFAULT font-bold">{{ $score }}</span>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-l from-brand-gold to-brand-orange rounded-full transition-all duration-500" 
                                 style="width: {{ ($score / $maxScore) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500">لا توجد درجات مسجلة</p>
                @endif
            </div>

            {{-- Counselor Notes Form --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-brand-gold"></i>
                    ملاحظات المستشار
                </h3>
                
                <form action="{{ route('admin.attempts.update', $attempt->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">المستشار المسؤول</label>
                        <select name="counselor_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
                            <option value="">-- اختر المستشار --</option>
                            @foreach($counselors as $counselor)
                            <option value="{{ $counselor->id }}" {{ $attempt->counselor_id == $counselor->id ? 'selected' : '' }}>
                                {{ $counselor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">الحالة</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
                            <option value="completed" {{ $attempt->status == 'completed' ? 'selected' : '' }}>جديد</option>
                            <option value="viewed" {{ $attempt->status == 'viewed' ? 'selected' : '' }}>تم الاطلاع</option>
                            <option value="reviewed" {{ $attempt->status == 'reviewed' ? 'selected' : '' }}>تمت المراجعة</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">ملاحظات</label>
                        <textarea name="counselor_notes" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold focus:border-brand-gold resize-none"
                                  placeholder="أضف ملاحظاتك هنا...">{{ $attempt->counselor_notes }}</textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-brand-gold text-brand-dark py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الملاحظات
                    </button>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            
            {{-- Client Info --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-brand-gold"></i>
                    معلومات العميل
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-brand-DEFAULT/10 flex items-center justify-center">
                            <i class="fas fa-user text-2xl text-brand-DEFAULT"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $attempt->client_name }}</div>
                            <div class="text-sm text-gray-500">
                                @if($attempt->user)
                                    <span class="text-green-600"><i class="fas fa-check-circle ml-1"></i> مستخدم مسجل</span>
                                @else
                                    <span class="text-gray-400"><i class="fas fa-user-clock ml-1"></i> زائر</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($attempt->client_email)
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <a href="mailto:{{ $attempt->client_email }}" class="hover:text-brand-DEFAULT">{{ $attempt->client_email }}</a>
                    </div>
                    @endif
                    
                    @if($attempt->client_phone)
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <a href="tel:{{ $attempt->client_phone }}" class="hover:text-brand-DEFAULT" dir="ltr">{{ $attempt->client_phone }}</a>
                    </div>
                    @endif
                    
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-globe w-5 text-gray-400"></i>
                        <span>{{ $attempt->ip_address ?? 'غير معروف' }}</span>
                    </div>
                </div>
            </div>

            {{-- Test Info --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-brand-gold"></i>
                    معلومات الاختبار
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">الاختبار:</span>
                        <span class="text-gray-900 font-medium">{{ $attempt->assessment_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">التاريخ:</span>
                        <span class="text-gray-900">{{ $attempt->created_at->format('Y/m/d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">الوقت:</span>
                        <span class="text-gray-900">{{ $attempt->created_at->format('h:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">الحالة:</span>
                        @if($attempt->status === 'completed')
                            <span class="text-yellow-600">جديد</span>
                        @elseif($attempt->status === 'viewed')
                            <span class="text-blue-600">تم الاطلاع</span>
                        @else
                            <span class="text-green-600">تمت المراجعة</span>
                        @endif
                    </div>
                    @if($attempt->counselor)
                    <div class="flex justify-between">
                        <span class="text-gray-500">المستشار:</span>
                        <span class="text-gray-900">{{ $attempt->counselor->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-brand-gold"></i>
                    إجراءات سريعة
                </h3>
                
                <div class="space-y-2">
                    @if($attempt->client_phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $attempt->client_phone) }}" 
                       target="_blank"
                       class="flex items-center gap-3 p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                        <i class="fab fa-whatsapp"></i>
                        <span>تواصل عبر واتساب</span>
                    </a>
                    <a href="tel:{{ $attempt->client_phone }}" 
                       class="flex items-center gap-3 p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-phone"></i>
                        <span>اتصال هاتفي</span>
                    </a>
                    @endif
                    @if($attempt->client_email)
                    <a href="mailto:{{ $attempt->client_email }}" 
                       class="flex items-center gap-3 p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                        <i class="fas fa-envelope"></i>
                        <span>إرسال بريد إلكتروني</span>
                    </a>
                    @endif
                    <form action="{{ route('admin.attempts.destroy', $attempt->id) }}" method="POST" 
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه النتيجة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center gap-3 p-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-trash"></i>
                            <span>حذف النتيجة</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


