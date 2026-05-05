@extends('layouts.admin')

@section('title', 'تعديل - ' . $config['label'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.content.index', $type) }}" class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">تعديل: {{ $item->title }}</h2>
            <p class="text-brand-textMuted mt-1">{{ $config['label'] }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.content.update', [$type, $item->id]) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.content._form', ['item' => $item])
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.content.index', $type) }}" class="px-6 py-3 border border-brand-border rounded-lg text-brand-dark hover:bg-gray-50 transition font-medium">
                إلغاء
            </a>
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                <i class="fas fa-save"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection
