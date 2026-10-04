@extends('layouts.public')

@section('title', 'غرفة الجلسة - ' . $booking->booking_number)

@section('content')
<section class="bg-gray-50 py-8" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-brand-dark">جلسة مع {{ $otherUserName }}</h1>
                <p class="text-brand-textMuted mt-1">
                    {{ $booking->sessionStartsAt()->locale('ar')->translatedFormat('l j F Y') }}
                    · {{ $booking->formatted_time }}
                    · حجز {{ $booking->booking_number }}
                </p>
            </div>
            @if($state === 'live' && $isConsultant)
            <form method="POST" action="{{ route('video-call.end', $booking) }}"
                  onsubmit="return confirm('إنهاء الجلسة للطرفين وإغلاق الغرفة؟')">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-red-700 transition">
                    إنهاء الجلسة
                </button>
            </form>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- Video area --}}
            <div class="lg:col-span-3">
                @if($state === 'live')
                    <div id="daily-frame" class="w-full bg-black rounded-2xl overflow-hidden" style="height: 70vh; min-height: 420px;"></div>
                    <div id="left-meeting" class="hidden mt-4 bg-white rounded-2xl p-6 text-center border border-brand-border">
                        <p class="text-brand-dark font-bold mb-3">غادرت الجلسة</p>
                        <button type="button" onclick="location.reload()" class="bg-brand-gold text-brand-dark px-5 py-2.5 rounded-xl font-bold">العودة للجلسة</button>
                    </div>
                @elseif($state === 'waiting')
                    <div class="bg-white rounded-2xl p-10 text-center border border-brand-border">
                        <div class="text-5xl mb-4">⏳</div>
                        <h2 class="text-xl font-bold text-brand-dark mb-2">الجلسة لم تبدأ بعد</h2>
                        <p class="text-brand-textMuted mb-6">
                            تُفتح الغرفة قبل الموعد بـ {{ \App\Models\Booking::JOIN_OPENS_MINUTES_BEFORE }} دقائق،
                            الساعة {{ $booking->joinOpensAt()->format('h:i A') }}.
                        </p>
                        <p class="text-3xl font-bold text-brand-gold" id="countdown" dir="ltr"></p>
                        <p class="text-sm text-brand-textMuted mt-6">يمكنك مشاركة الملفات مع {{ $isConsultant ? 'العميل' : 'المستشار' }} الآن من القائمة الجانبية.</p>
                    </div>
                @elseif($state === 'ended')
                    <div class="bg-white rounded-2xl p-10 text-center border border-brand-border">
                        <div class="text-5xl mb-4">✅</div>
                        <h2 class="text-xl font-bold text-brand-dark mb-2">انتهت الجلسة</h2>
                        <p class="text-brand-textMuted">الملفات المشتركة ما زالت متاحة للتحميل من القائمة الجانبية.</p>
                    </div>
                @else
                    <div class="bg-white rounded-2xl p-10 text-center border border-red-200">
                        <div class="text-5xl mb-4">⚠️</div>
                        <h2 class="text-xl font-bold text-brand-dark mb-2">تعذر فتح غرفة الجلسة حاليًا</h2>
                        <p class="text-brand-textMuted mb-6">حدّث الصفحة بعد لحظات. إن استمرت المشكلة تواصل معنا.</p>
                        <button type="button" onclick="location.reload()" class="bg-brand-gold text-brand-dark px-5 py-2.5 rounded-xl font-bold">تحديث</button>
                    </div>
                @endif
            </div>

            {{-- Shared files --}}
            <aside class="bg-white rounded-2xl border border-brand-border p-5 h-fit">
                <h3 class="font-bold text-brand-dark mb-4">📎 ملفات الجلسة</h3>

                <ul id="file-list" class="space-y-3 mb-5 text-sm"></ul>
                <p id="no-files" class="text-sm text-brand-textMuted mb-5">لا توجد ملفات بعد.</p>

                <form id="upload-form" class="space-y-3">
                    <input type="file" name="file" id="file-input" required
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-brand-textMuted file:ml-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-gold/20 file:text-brand-dark">
                    <button type="submit" id="upload-button" class="w-full bg-brand-gold text-brand-dark py-2.5 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                        رفع الملف
                    </button>
                    <p class="text-xs text-brand-textMuted">PDF أو Word أو Excel أو PowerPoint أو صور، حتى 10 ميجابايت. لا يراها إلا طرفا الجلسة.</p>
                    <p id="upload-error" class="hidden text-xs text-red-600"></p>
                </form>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@if($state === 'live')
<script src="https://unpkg.com/@daily-co/daily-js"></script>
<script>
    (function () {
        const callFrame = window.DailyIframe.createFrame(document.getElementById('daily-frame'), {
            iframeStyle: { width: '100%', height: '100%', border: '0' },
            showLeaveButton: true,
            showFullscreenButton: true,
        });
        callFrame.join({ url: @json($roomUrl), token: @json($token) });
        callFrame.on('left-meeting', function () {
            document.getElementById('daily-frame').classList.add('hidden');
            document.getElementById('left-meeting').classList.remove('hidden');
        });
    })();
</script>
@endif

@if($state === 'waiting')
<script>
    (function () {
        const opensAt = {{ $booking->joinOpensAt()->timestamp }} * 1000;
        const el = document.getElementById('countdown');
        function tick() {
            const left = Math.max(0, Math.floor((opensAt - Date.now()) / 1000));
            if (left === 0) { location.reload(); return; }
            const d = Math.floor(left / 86400), h = Math.floor(left % 86400 / 3600),
                  m = Math.floor(left % 3600 / 60), s = left % 60;
            el.textContent = (d ? d + 'd ' : '') + String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        }
        tick();
        setInterval(tick, 1000);
    })();
</script>
@endif

<script>
    (function () {
        const listUrl = @json(route('video-call.files', $videoCall));
        const uploadUrl = @json(route('video-call.upload-file', $videoCall));
        const csrf = @json(csrf_token());
        const list = document.getElementById('file-list');
        const empty = document.getElementById('no-files');
        const errorEl = document.getElementById('upload-error');

        function render(files) {
            list.innerHTML = '';
            empty.classList.toggle('hidden', files.length > 0);
            files.forEach(function (f) {
                const li = document.createElement('li');
                li.className = 'flex items-start justify-between gap-2 border-b border-brand-border pb-2';
                const info = document.createElement('div');
                const link = document.createElement('a');
                link.href = f.url;
                link.textContent = f.name;
                link.className = 'text-brand-dark font-medium hover:text-brand-gold break-all';
                const meta = document.createElement('p');
                meta.className = 'text-xs text-brand-textMuted';
                meta.textContent = (f.mine ? 'أنت' : f.user_name) + ' · ' + f.size + ' · ' + f.time;
                info.append(link, meta);
                li.append(info);
                list.append(li);
            });
        }

        function refresh() {
            fetch(listUrl, { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) { render(data.files); })
                .catch(function () {});
        }

        document.getElementById('upload-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const input = document.getElementById('file-input');
            if (!input.files.length) return;
            const button = document.getElementById('upload-button');
            const body = new FormData();
            body.append('file', input.files[0]);
            button.disabled = true;
            button.textContent = 'جاري الرفع...';
            errorEl.classList.add('hidden');

            fetch(uploadUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: body })
                .then(function (r) { return r.json().then(function (data) { return { ok: r.ok, data: data }; }); })
                .then(function (res) {
                    if (!res.ok) {
                        errorEl.textContent = (res.data.errors && res.data.errors.file && res.data.errors.file[0]) || res.data.message || 'تعذر رفع الملف';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                    input.value = '';
                    refresh();
                })
                .catch(function () {
                    errorEl.textContent = 'تعذر رفع الملف';
                    errorEl.classList.remove('hidden');
                })
                .finally(function () {
                    button.disabled = false;
                    button.textContent = 'رفع الملف';
                });
        });

        render(@json($files));
        setInterval(refresh, 15000);
    })();
</script>
@endpush
