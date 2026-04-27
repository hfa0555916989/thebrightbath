@extends('layouts.admin')

@section('title', 'إعدادات العمولة')
@section('page-title', 'إعدادات العمولة')

@section('content')
<div class="max-w-xl">
    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.finance.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">نسبة العمولة من كل جلسة (%)</label>
                <input type="number" name="commission_rate" value="{{ old('commission_rate', $commissionRate) }}" 
                       min="0" max="100" step="0.1" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                <p class="text-sm text-gray-500 mt-1">النسبة التي يحصل عليها الموقع من كل جلسة استشارية</p>
                @error('commission_rate')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="bg-brand-gold/10 rounded-xl p-4 mb-6">
                <h4 class="font-bold text-brand-dark mb-2">مثال:</h4>
                <p class="text-sm text-gray-600">
                    إذا كانت نسبة العمولة {{ $commissionRate }}% وسعر الجلسة 100 ر.س:<br>
                    <strong>أرباح المنصة:</strong> {{ $commissionRate }} ر.س<br>
                    <strong>أرباح المستشار:</strong> {{ 100 - $commissionRate }} ر.س
                </p>
            </div>

            <button type="submit" class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                <i class="fas fa-save ml-2"></i>
                حفظ الإعدادات
            </button>
        </form>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.finance.index') }}" class="text-brand-gold hover:underline">
            <i class="fas fa-arrow-right ml-1"></i> العودة للتقارير المالية
        </a>
    </div>
</div>
@endsection




