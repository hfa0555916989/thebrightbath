@extends('layouts.consultant')

@section('title', 'إدارة المواعيد')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-2xl font-bold text-gray-900">⏰ إدارة جدول المواعيد</h1>
                <p class="text-gray-500 mt-1">حدد أوقات توفرك للجلسات الاستشارية</p>
            </div>

            <form action="{{ route('consultant.schedule.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    @foreach($days as $dayNum => $dayName)
                        @php
                            $schedule = $schedules->firstWhere('day_of_week', $dayNum);
                        @endphp
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-24">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="schedule[{{ $dayNum }}][available]" 
                                           value="1"
                                           {{ $schedule && $schedule->is_available ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 ml-2"
                                           onchange="toggleDayInputs(this, {{ $dayNum }})">
                                    <span class="font-semibold text-gray-700">{{ $dayName }}</span>
                                </label>
                            </div>
                            <div class="flex-1 flex items-center gap-4 mr-8" id="day-{{ $dayNum }}-inputs">
                                <div class="flex items-center">
                                    <label class="text-sm text-gray-500 ml-2">من</label>
                                    <input type="time" 
                                           name="schedule[{{ $dayNum }}][start_time]" 
                                           value="{{ $schedule ? $schedule->start_time : '09:00' }}"
                                           class="rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 {{ $schedule && $schedule->is_available ? '' : 'opacity-50' }}">
                                </div>
                                <div class="flex items-center">
                                    <label class="text-sm text-gray-500 ml-2">إلى</label>
                                    <input type="time" 
                                           name="schedule[{{ $dayNum }}][end_time]" 
                                           value="{{ $schedule ? $schedule->end_time : '17:00' }}"
                                           class="rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 {{ $schedule && $schedule->is_available ? '' : 'opacity-50' }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition">
                        💾 حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleDayInputs(checkbox, day) {
    const inputs = document.querySelectorAll(`#day-${day}-inputs input[type="time"]`);
    inputs.forEach(input => {
        input.classList.toggle('opacity-50', !checkbox.checked);
    });
}
</script>
@endsection



