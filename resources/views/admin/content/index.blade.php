@extends('layouts.admin')

@section('title', $config['label'])

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">{{ $config['label'] }}</h2>
            <p class="text-brand-textMuted mt-1">إدارة وترتيب العناصر - اسحب وأفلت لتغيير الترتيب</p>
        </div>
        <a href="{{ route('admin.content.create', $type) }}"
            class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-5 py-2.5 rounded-lg font-bold hover:bg-brand-goldDeep transition">
            <i class="fas fa-plus"></i> إضافة عنصر جديد
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
            <div class="text-3xl font-bold text-brand-gold">{{ $items->count() }}</div>
            <div class="text-sm text-brand-textMuted mt-1">إجمالي العناصر</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
            <div class="text-3xl font-bold text-green-600">{{ $items->where('is_active', true)->count() }}</div>
            <div class="text-sm text-brand-textMuted mt-1">نشط</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
            <div class="text-3xl font-bold text-red-500">{{ $items->where('is_active', false)->count() }}</div>
            <div class="text-sm text-brand-textMuted mt-1">معطّل</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
            <div class="text-3xl font-bold text-brand-DEFAULT">{{ $items->whereNotNull('image')->count() }}</div>
            <div class="text-sm text-brand-textMuted mt-1">بصورة</div>
        </div>
    </div>

    @if($items->isEmpty())
        <div class="bg-white rounded-xl p-12 shadow-sm border border-gray-100 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-dark mb-2">لا توجد عناصر بعد</h3>
            <p class="text-brand-textMuted mb-4">ابدأ بإضافة العنصر الأول</p>
            <a href="{{ route('admin.content.create', $type) }}" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-5 py-2.5 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                <i class="fas fa-plus"></i> إضافة الآن
            </a>
        </div>
    @else
        {{-- Sortable List --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-10 px-4 py-4"></th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">العنصر</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600 hidden md:table-cell">التفاصيل</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="sortable-list" class="divide-y divide-gray-100">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50 transition" data-id="{{ $item->id }}">
                        {{-- Drag Handle --}}
                        <td class="px-4 py-4">
                            <div class="drag-handle cursor-grab text-gray-300 hover:text-gray-500 flex justify-center">
                                <i class="fas fa-grip-vertical text-lg"></i>
                            </div>
                        </td>
                        {{-- Title + Icon + Color --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($item->image)
                                    <img src="{{ $item->image_url }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                @elseif($item->icon)
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0"
                                        style="background-color: {{ $item->color ?? '#F8C524' }}20">
                                        <i class="{{ $item->icon }}" style="color: {{ $item->color ?? '#F8C524' }}"></i>
                                    </div>
                                @elseif($item->color)
                                    <div class="w-4 h-12 rounded-full flex-shrink-0" style="background-color: {{ $item->color }}"></div>
                                @endif
                                <div>
                                    <div class="font-medium text-brand-dark">{{ $item->title }}</div>
                                    @if($item->subtitle)
                                        <div class="text-sm text-brand-textMuted">{{ $item->subtitle }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        {{-- Details --}}
                        <td class="px-6 py-4 hidden md:table-cell">
                            @if($item->body)
                                <p class="text-sm text-brand-textMuted line-clamp-2 max-w-xs">{{ $item->body }}</p>
                            @endif
                            @if($item->icon)
                                <span class="text-xs text-gray-400 font-mono">{{ $item->icon }}</span>
                            @endif
                        </td>
                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.content.toggle', [$type, $item->id]) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium transition
                                    {{ $item->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                                    <i class="fas fa-{{ $item->is_active ? 'eye' : 'eye-slash' }}"></i>
                                    {{ $item->is_active ? 'نشط' : 'معطّل' }}
                                </button>
                            </form>
                        </td>
                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.content.edit', [$type, $item->id]) }}"
                                    class="w-9 h-9 rounded-lg bg-brand-gold/20 text-brand-goldDeep flex items-center justify-center hover:bg-brand-gold/30 transition"
                                    title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.content.destroy', [$type, $item->id]) }}"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 rounded-lg bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition"
                                        title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
const list = document.getElementById('sortable-list');
if (list) {
    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function() {
            const ids = [...list.querySelectorAll('tr[data-id]')].map(tr => tr.dataset.id);
            fetch('{{ route('admin.content.reorder', $type) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids })
            });
        }
    });
}
</script>
@endsection
