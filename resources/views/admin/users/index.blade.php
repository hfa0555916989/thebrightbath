@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')
@section('header', 'إدارة المستخدمين')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">إدارة المستخدمين</h2>
            <p class="text-brand-textMuted mt-1">قائمة المستخدمين المسجلين في المنصة</p>
        </div>
        <div class="text-sm text-brand-textMuted">
            إجمالي المستخدمين: <span class="font-bold text-brand-dark">{{ $users->total() }}</span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">إجمالي المستخدمين</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">{{ $users->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-brand-gold text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">مسجلين اليوم</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ \App\Models\User::whereDate('created_at', today())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-plus text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">هذا الأسبوع</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ \App\Models\User::where('created_at', '>=', now()->startOfWeek())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-week text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">هذا الشهر</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ \App\Models\User::where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-brand-border overflow-hidden">
        <div class="p-6 border-b border-brand-border">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-bold text-brand-dark">قائمة المستخدمين</h3>
                <form action="" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="بحث بالاسم أو البريد..."
                           class="px-4 py-2 border border-brand-border rounded-lg text-sm focus:ring-2 focus:ring-brand-gold/20 w-64">
                    <button type="submit" class="px-4 py-2 bg-brand-DEFAULT text-white rounded-lg hover:bg-brand-dark transition">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">البريد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">تاريخ التسجيل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الاختبارات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-brand-textMuted">{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-brand-gold/20 rounded-full flex items-center justify-center">
                                        <span class="text-brand-gold font-bold">{{ mb_substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-brand-dark">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-brand-textMuted">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="px-2 py-1 bg-brand-gold/20 text-brand-gold rounded-full text-xs font-medium">مدير</span>
                                @elseif($user->role === 'counselor')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">مستشار</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">عميل</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-brand-textMuted">
                                {{ $user->created_at->format('Y/m/d') }}
                                <br>
                                <span class="text-xs">{{ $user->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $attemptsCount = \App\Models\AssessmentAttempt::where('user_id', $user->id)->count();
                                @endphp
                                @if($attemptsCount > 0)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">{{ $attemptsCount }} اختبار</span>
                                @else
                                    <span class="text-brand-textMuted">لم يجرِ أي اختبار</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    {{-- WhatsApp Contact Button --}}
                                    @php
                                        $message = "السلام عليكم {$user->name}،\n\nشكراً لتسجيلك في منصة الطريق المشرق! 🌟\n\nهل تحتاج مساعدة في اختيار الاختبار المناسب لك؟ أو لديك أي استفسار؟\n\ننحن هنا لمساعدتك! 💪";
                                        $encodedMessage = urlencode($message);
                                    @endphp
                                    <a href="https://wa.me/966543494316?text={{ $encodedMessage }}" 
                                       target="_blank"
                                       class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition"
                                       title="تواصل عبر واتساب">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    
                                    {{-- Email Button --}}
                                    <a href="mailto:{{ $user->email }}?subject=رسالة من الطريق المشرق&body=السلام عليكم {{ $user->name }}،" 
                                       class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                       title="إرسال بريد">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    
                                    {{-- View Attempts --}}
                                    @if($attemptsCount > 0)
                                        <a href="{{ route('admin.attempts.index') }}?user={{ $user->id }}" 
                                           class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition"
                                           title="عرض الاختبارات">
                                            <i class="fas fa-clipboard-list"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-brand-textMuted">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">لا يوجد مستخدمين</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-6 border-t border-brand-border">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Quick Actions --}}
    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <i class="fab fa-whatsapp text-green-600 text-2xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-green-800 mb-2">التواصل السريع عبر واتساب</h4>
                <p class="text-green-700 text-sm mb-3">
                    اضغط على أيقونة الواتساب بجانب كل مستخدم لإرسال رسالة ترحيبية تلقائية تسأله إذا كان يحتاج مساعدة.
                </p>
                <p class="text-green-600 text-xs">
                    💡 نصيحة: التواصل السريع مع المستخدمين الجدد يزيد من نسبة إتمام الاختبارات
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

