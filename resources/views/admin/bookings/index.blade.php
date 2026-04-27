@extends('layouts.admin')

@section('title', 'إدارة الحجوزات')
@section('page-title', 'إدارة الحجوزات')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">رقم الحجز</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">العميل</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">المستشار</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">التاريخ والوقت</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">المبلغ</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-sm">{{ $booking->booking_number }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-brand-dark">{{ $booking->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $booking->consultant->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <p>{{ $booking->booking_date->format('Y-m-d') }}</p>
                            <p class="text-sm text-gray-500">{{ date('h:i A', strtotime($booking->start_time)) }}</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-brand-gold">{{ number_format($booking->price) }} ر.س</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                @if($booking->status === 'confirmed') bg-green-100 text-green-700
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($booking->status === 'completed') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $booking->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-brand-gold hover:underline">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-calendar text-4xl mb-4"></i>
                            <p>لا توجد حجوزات</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>
@endsection




