# Bright Path Portal — دليل العمل

منصة إرشاد مهني عربية: Laravel 11 + MySQL + Blade/Tailwind/Alpine (Vite).
الأقسام: الموقع العام، لوحة العميل، لوحة المستشار (`/consultant`)، لوحة الإدارة (`/control-panel`).
الهدف: النشر على Laravel Cloud.

## بروتوكول كل مهمة
1. **في البداية:** اقرأ `PROGRESS.md` لتعرف أين وصلنا وما المهمة التالية.
2. أنشئ فرعًا للمهمة: `type/short-name` (مثل `feat/daily-meetings`) من آخر فرع معتمد.
3. **في النهاية:** حدّث `PROGRESS.md` (الحالة، القرارات، المشاكل المكتشفة)، ثم commit.
4. اعرض تقريرًا فيه: الملفات المعدلة، الأوامر المطلوب تشغيلها (migrations وغيرها)، متغيرات `.env` الجديدة.
5. لا push ولا Pull Request إلا بطلب صريح.

## قواعد ثابتة
- لا تعدّل شيئًا خارج نطاق المهمة. ما تكتشفه خارجها يُسجَّل في `PROGRESS.md` تحت "مشاكل معروفة".
- لا مفاتيح ولا كلمات مرور في الكود: كل شيء عبر `config/*.php` و`.env`، وكل متغير جديد يُضاف إلى `.env.example`.
- `route()` بدل الروابط المكتوبة يدويًا، في الـ views والإيميلات.
- Feature Test لكل سلوك جديد أو مُصلَح، والمجموعة كاملة تنجح قبل الـ commit.
- واجهة المستخدم ورسائل الخطأ بالعربية.
- الملفات بنهايات CRLF؛ حافظ على نمط الملف عند التعديل.

## المجلد والأدوات
- اعمل داخل `thebrightbath/` فقط. المجلد الأب `thebrightbath.com/` نسخة قديمة، لا تلمسها.
- PHP وComposer من Herd، ويعملان من PowerShell فقط (Git Bash لا يرى `php`).
- `vendor/` غير مرفوع في Git. بعد السحب شغّل `composer install`. عند إضافة حزمة اكتب القيد كاملًا (مثل `"^3.0"`)، لأن cmd يحذف `^` من الأوامر.
- هيكل Laravel القياسي: نقطة الدخول `public/index.php`، والملفات الثابتة في `public/` (images، favicon، robots).
- الاختبارات:
  ```
  php vendor/bin/phpunit
  ```
  - تعمل على MySQL منفصلة في Docker: حاوية `brightpath-testdb` على `127.0.0.1:33061`، قاعدة `brightpath_testing`.
  - الاتصال في `.env.testing` (غير مرفوع)، وشرحه في `.env.example`.
  - `tests/TestCase.php` يرفض التشغيل على أي قاعدة غيرها؛ لا تُضعف هذا الحارس.
  - إذا لم تكن الحاوية تعمل: `docker start brightpath-testdb`.
- **Laravel Cloud** (`https://thebrightbath-production-scv4tp.laravel.cloud`): البيئة تنشر تلقائيًا كل push على `main`.
  - العمل يتم في فروع، ولا يُدمج في `main` إلا ما نجحت اختباراته.
  - الدمج والرفع إلى `main` بموافقة المستخدم فقط.
- **لا تشغّل `db:seed` على الإنتاج:** `ContentItemsSeeder` يمسح جدول المحتوى.
- ملف `.env` المحلي يحمل إعدادات الإنتاج (Hostinger). لا تشغّل `migrate` أو `db:seed` بدون `--env=testing`.

## حقائق في الكود يجب احترامها
- **الأدوار** (`users.role`): `admin`، `counselor` (= المستشار)، `client`.
  - middleware `admin` (لوحة الإدارة) للأدمن فقط. المستشار له `/consultant`.
  - مسار لوحة الإدارة من `config('app.admin_path')` (`ADMIN_PATH`). في الكود استخدم دائمًا `route('admin.*')`.
- **الحماية من البوتات:** `App\Rules\Turnstile` و`<x-turnstile />` في التسجيل والدخول ونسيت كلمة المرور. معطّل ما لم تُضبط `TURNSTILE_*`.
- **حالات الحجز:** `pending_approval` ← `approved` (وافق المستشار) ← `confirmed` (دُفع) ← `completed`. وأيضًا `rejected` و`cancelled` و`no_show`.
- **الدفع:** بوابة مصرف الراجحي (تشغيل نيوليب)، تكامل Bank Hosted. المرجع: دليل "ARB Merchant Integration Guide – REST" v1.31.
  - `App\Services\NeoleapService`: التشفير (URL-encode ثم AES-256-CBC بـ IV ثابت `PGKEYENCDECIVSPC` ثم hex)، وطلب صفحة الدفع، ومعالجة النتيجة.
  - النتيجة تصل إلى `payment.neoleap.response` و`payment.neoleap.error`، كإشعار JSON من البنك أو كتحويل من متصفح العميل.
  - لا يُقبل الدفع إلا إذا فُك التشفير بمفتاحنا وطابق كل من: paymentId وtrackId والمبلغ والنتيجة `CAPTURED`.
  - الإشعار لا يُؤكَّد للبنك إلا بعد قبول الدفع؛ وبدون التأكيد يلغي البنك العملية.
  - `BookingPaymentService::confirm()` هو المكان الوحيد الذي يجعل الحجز مدفوعًا ومؤكدًا.
  - الاسترداد: `NeoleapService::refundBooking()` (action 2). الاستعلام: `reconcilePending()` (action 8)، عبر الأمر المجدول `payments:reconcile`.
  - بيانات الربط تُدخل من لوحة الإدارة، وتُخزن في `payment_settings` (صف `gateway = neoleap`). كلمة المرور والمفتاح مشفرة بـ `APP_KEY`.
- **الملفات المرفوعة:** عبر `store_upload()` و`delete_upload()` و`storage_asset()` في `app/Helpers/settings.php` فقط، على قرص `uploads`. القاعدة تحفظ المسار بصيغة `uploads/<folder>/<file>`.
  - الملفات الخاصة (نماذج التحليل) على قرص `private`.
  - على Laravel Cloud تحل الـ buckets بنفس الاسمين محل القرصين تلقائيًا.
  - لا تكتب ملفات مباشرة في `public_path()`: نظام ملفات Cloud يُمسح مع كل نشر.
- **صور الموقع** (الشعار، favicon، og): عبر `site_image('site_logo', 'images/...')` من إعدادات الموقع، مع ملف افتراضي في `public/`.
- **البريد:** عبر Resend (`MAIL_MAILER=resend` و`RESEND_KEY`). كل الـ Mailables تنفذ `ShouldQueue`، فاستخدم `Mail::assertQueued` في الاختبارات وليس `assertSent`. الروابط داخل الإيميلات عبر `route()` فقط.
- **الجلسات (Daily.co):**
  - `App\Services\DailyService` ينشئ غرفة خاصة لكل حجز عند أول دخول، ويصدر meeting token لكل طرف (المستشار owner).
  - نافذة الدخول من `Booking::joinOpensAt()` إلى `joinClosesAt()`: قبل البداية بـ10 دقائق حتى بعد النهاية بـ30 دقيقة.
  - ملفات الجلسة على قرص `private`، ولطرفي الجلسة فقط.
  - المفتاح `DAILY_API_KEY`، وفي الاختبارات `Http::fake`.
- **التذكيرات:** `App\Services\SessionReminderService` عبر `sessions:send-reminders` (Scheduler كل 5 دقائق). التقويم عبر `App\Support\BookingCalendar::ics()`.
- **الأوامر المجدولة كلها** في `routes/console.php` وتحتاج Scheduler مفعّل (Laravel Cloud).
- **Factories** موجودة للمستخدم والمستشار والحجز والدفعة، بحالات جاهزة.
