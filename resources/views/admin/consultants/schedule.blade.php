@extends('layouts.admin')

@section('title', 'جدول مواعيد المستشار')
@section('page-title', 'جدول مواعيد: ' . $consultant->user->name)

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.consultants.update-schedule', $consultant) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4 mb-6">
                @if($consultant->photo)
                    <img src="{{ asset('storage/' . $consultant->photo) }}" class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-brand-gold/20 flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-brand-gold"></i>
                    </div>
                @endif
                <div>
                    <h3 class="text-xl font-bold text-brand-dark">{{ $consultant->user->name }}</h3>
                    <p class="text-gray-500">{{ $consultant->specialization_ar }}</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">تعليمات:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>حدد الأيام المتاحة للمستشار</li>
                            <li>حدد وقت البداية والنهاية لكل يوم</li>
                            <li>سيتمكن العملاء من الحجز ضمن هذه الأوقات</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($days as $dayNum => $dayName)
                @php
                    $schedule = $consultant->schedules->where('day_of_week', $dayNum)->first();
                @endphp
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <input type="hidden" name="schedules[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">
                    
                    <label class="flex items-center gap-3 w-32 cursor-pointer">
                        <input type="checkbox" name="schedules[{{ $dayNum }}][is_available]" value="1" 
                               {{ $schedule && $schedule->is_available ? 'checked' : '' }}
                               class="w-5 h-5 text-brand-gold rounded focus:ring-brand-gold schedule-toggle"
                               data-day="{{ $dayNum }}">
                        <span class="font-medium text-brand-dark">{{ $dayName }}</span>
                    </label>

                    <div class="flex items-center gap-3 flex-1" id="times-{{ $dayNum }}">
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-500">من</label>
                            <input type="time" name="schedules[{{ $dayNum }}][start_time]" 
                                   value="{{ $schedule ? substr($schedule->start_time, 0, 5) : '09:00' }}"
                                   class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold time-input">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-500">إلى</label>
                            <input type="time" name="schedules[{{ $dayNum }}][end_time]" 
                                   value="{{ $schedule ? substr($schedule->end_time, 0, 5) : '17:00' }}"
                                   class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold time-input">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-brand-gold text-brand-dark px-8 py-3 rounded-xl font-medium hover:bg-brand-goldDeep transition">
                <i class="fas fa-save ml-2"></i>
                حفظ الجدول
            </button>
            <a href="{{ route('admin.consultants.index') }}" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-medium hover:bg-gray-300 transition">
                رجوع
            </a>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.schedule-toggle').forEach(checkbox => {
    const day = checkbox.dataset.day;
    const timesDiv = document.getElementById('times-' + day);
    
    function toggle() {
        timesDiv.style.opacity = checkbox.checked ? '1' : '0.5';
        timesDiv.querySelectorAll('input').forEach(input => {
            input.disabled = !checkbox.checked;
        });
    }
    
    toggle();
    checkbox.addEventListener('change', toggle);
});
</script>
@endsection




