@extends('layouts.public')

@section('title', 'نتائج اختباراتي')

@section('content')
<section class="pt-28 pb-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-brand-dark">نتائج اختباراتي</h1>
                <a href="{{ route('client.dashboard') }}" class="text-brand-gold hover:underline">
                    <i class="fas fa-arrow-right ml-1"></i> العودة
                </a>
            </div>

            @if($results->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-clipboard-list text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-600 mb-2">لم تقم بأي اختبار بعد</h2>
                <p class="text-gray-500 mb-6">ابدأ بإجراء اختبارات الميول لاكتشاف شخصيتك المهنية</p>
                <a href="{{ route('assessments.index') }}" class="bg-brand-gold text-brand-dark px-8 py-3 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                    ابدأ أول اختبار
                </a>
            </div>
            @else
            <div class="space-y-4">
                @foreach($results as $result)
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-brand-dark mb-2">{{ $result->assessment_name }}</h3>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-calendar ml-1"></i>
                                {{ $result->created_at->format('Y-m-d') }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-clock ml-1"></i>
                                {{ $result->created_at->format('h:i A') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-center px-4">
                                <p class="text-3xl font-bold text-brand-gold">{{ $result->type_code }}</p>
                                <p class="text-xs text-gray-500">نتيجتك</p>
                            </div>
                            <a href="{{ route('client.result.show', $result->id) }}" 
                               class="bg-brand-gold text-brand-dark px-6 py-3 rounded-xl font-bold hover:bg-brand-goldDeep transition flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                <span>عرض التفاصيل</span>
                            </a>
                        </div>
                    </div>
                    @if($result->summary)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">{{ $result->summary }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $results->links() }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection




