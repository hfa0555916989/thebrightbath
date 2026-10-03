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
- الاختبارات:
  ```
  php vendor/bin/phpunit
  ```
  - تعمل على MySQL منفصلة في Docker: حاوية `brightpath-testdb` على `127.0.0.1:33061`، قاعدة `brightpath_testing`.
  - الاتصال في `.env.testing` (غير مرفوع)، وشرحه في `.env.example`.
  - `tests/TestCase.php` يرفض التشغيل على أي قاعدة غيرها؛ لا تُضعف هذا الحارس.
  - إذا لم تكن الحاوية تعمل: `docker start brightpath-testdb`.
- ملف `.env` المحلي يحمل إعدادات الإنتاج (Hostinger). لا تشغّل `migrate` أو `db:seed` بدون `--env=testing`.

## حقائق في الكود يجب احترامها
- **الأدوار** (`users.role`): `admin`، `counselor` (= المستشار)، `client`.
  - middleware `admin` يسمح للأدمن والمستشار.
  - `admin:strict` للأدمن فقط (المالية وإعدادات الدفع).
- **حالات الحجز:** `pending_approval` ← `approved` (وافق المستشار) ← `confirmed` (دُفع) ← `completed`. وأيضًا `rejected` و`cancelled` و`no_show`.
- **الدفع:** Paymob KSA V2.
  - `BookingPaymentService::confirm()` هو المكان الوحيد الذي يجعل الحجز مدفوعًا ومؤكدًا.
  - الـ webhook يرفض أي إشعار بدون HMAC صحيح.
- **Factories** موجودة للمستخدم والمستشار والحجز والدفعة، بحالات جاهزة.
