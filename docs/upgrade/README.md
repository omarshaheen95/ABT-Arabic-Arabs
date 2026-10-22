# ترقية arabic-arabs إلى Laravel 13 و PHP 8.4 (لم تبدأ بعد)

هذا المجلد منقول من المشاريع السابقة في الخطة ليكون نقطة البداية هنا. لم يُعدَّل أي شيء في كود arabic-arabs حتى الآن.

| الملف | ما هو |
| --- | --- |
| `خطة تحديث مشاريع ABT.md` (في جذر المشروع) | الخطة العامة لكل المشاريع |
| `nonarab-reference.md` | ملاحظات nonarab قبل بدء ترقيته. **nonarab هو الأقرب لهذا المشروع** (Laravel 8.83.29، نفس الـ guards، نفس `SettingServiceProvider` وTelegram ونمط الدروس والقصص). ترقيته كانت جارية على فرع `upgrade/laravel-13` وقت النسخ؛ إن انتهت فاقرأ `docs/upgrade/README.md` هناك أولاً |
| `readingbenchmark-reference.md` | ترقية من Laravel 7، وأكمل المراجع توثيقاً: ثغرة الـ IP، class `Match`، مقارنة `config()`، تعطيل Telegram، "دروس للمشروع التالي" |
| `socialuae-reference.md` | ترقية من Laravel 7: `session.serialization` و`cache.serializable_classes` وصفحات الأخطاء وقوالب البريد |
| `islamic-reference.md` | الترقية الأولى (من Laravel 6) ودروسها |
| `harness/` | أدوات المقارنة **كما كانت في nonarab أثناء تكييفها له** (نسخة غير نهائية). أقرب ما يكون لهذا المشروع، لكن أسماء الـ routes والـ models والبيانات تُراجع قبل التشغيل |

## ما يميز arabic-arabs
- **Laravel 8.83.29** (المثبت في vendor)، وPHP `^7.3|^8.0`.
- `composer.lock` **موجود** في git (بخلاف nonarab وsocialuae)، ومعه `composer_.lock` قديم لا يُستخدم على ما يبدو.
- **الـ guards**: `manager` و`school` و`teacher` و`supervisor` و`web` (الطلاب، model `User`). الـ models في `app/Models`، ومنها `Permission` و`Role` خاصة بالمشروع.
- **قاعدة البيانات المحلية** `arabicarabs` (فيها قرابة 71 ألف طالب).
- **ملفات الـ routes**: `web` `general` `manager` `school` `teacher` `supervisor` `api`، وبصيغة النص `'Controller@method'` مع namespace في المجموعات، فتحتاج تحويلاً كما في islamic.
- `resources/lang` ليست في git (ما عدا `auth_log.php`)، وتُنسخ عند النشر. لا يوجد `resources/views/errors`، و`resources/views/vendor` فيها `jsvalidation` و`log-viewer` فقط (قوالب البريد غير منشورة).
- الـ scheduler في `app/Console/Kernel.php`: `login-sessions:prune` يومياً و`user:check-expire` مرتين يومياً.
- الاختبارات الموجودة: `LoginActivityTest` والأمثلة فقط.
- **الحزم وقرار كل منها** (المثبت في vendor):
  - `spatie/laravel-activitylog` **3.17**: الصيغة القديمة (`$logAttributes` الثابتة) تتغير في 4 إلى `getActivitylogOptions()`، كما في islamic.
  - `spatie/laravel-medialibrary` **7.20**: أقدم حزمة هنا، والقفز إلى 11 يغيّر الـ namespace (`Spatie\MediaLibrary\...`) وجدول `media` (أعمدة `uuid` و`conversions_disk` و`generated_conversions`). تستخدمها `Lesson` و`Option` و`QMatch` وغيرها، وفي القاعدة المحلية 6793 ملفاً في جدول `media`. لم تظهر في المشاريع السابقة، فهي أكبر مخاطرة: تُفحص روابط الملفات القديمة قبل وبعد.
  - `hesto/multi-auth` 2.0: متروك ولا يدعم Laravel 13، لكن controllers الدخول (`ManagerAuth` و`SchoolAuth` وغيرها) تستخدم منه trait `LogsoutGuard` وقت التشغيل، فيُنقل الـ trait إلى المشروع قبل حذف الحزمة.
  - `fruitcake/laravel-cors`: يُحذف، فالـ CORS مدمج في Laravel منذ 9.
  - `spatie/laravel-permission` 5.11، `laravel/sanctum` 2.15، `yajra/laravel-datatables-oracle` 9، `maatwebsite/excel` 3.1، `proengsoft/laravel-jsvalidation` 4.7، `propaganistas/laravel-phone` 4، `simplesoftwareio/simple-qrcode` 4، `dyrynda/laravel-cascade-soft-deletes` 4، `arcanedev/log-viewer` 8، `laravel/ui` 3.

## ما رُصد قبل البدء ويجب عرضه على صاحب المشروع
- **routes عامة تشغّل أوامر artisan بلا أي حماية** في `routes/web.php` (حوالي السطر 95): `GET /migrate` يشغّل `migrate`، و`GET /cache` يشغّل `cache:clear` و`config:cache`، و`GET /view` يشغّل `view:clear`، و`GET /command` يشغّل `schedule:work` الذي لا ينتهي أبداً فيحجز عملية PHP مع كل طلب. أي زائر يستطيع استدعاءها. وفي `routes/manager.php` route `seed` يشغّل `SettingsTableSeeder` (خلف دخول المدير على الأقل). يُقرَّر حذفها أو حمايتها قبل أي شيء آخر.
- **ثغرة الـ IP نفسها**: `getRealIpAddress()` في `app/Helpers/helpers.php` تقرأ `X-Forwarded-For` أولاً، و`isTrustIpAddress()` تحمي `POST api/update_settings` (`Manager\SettingController@updateSettingsApi`) الذي يستطيع تغيير الإعدادات ومنها SMTP. أُصلحت في readingbenchmark وislamic بـ `$request->ip()` مع `TrustProxies`، ومعها `TrustedIpApiTest`.
- **hash كلمة المرور في activity log**: `app/Traits/LogsActivityTrait.php` يسجّل `['*']` ولا يستثني `password`، ويستخدمه `User` و`Manager` و`School` و`Teacher` و`Supervisor` وغيرها. في القاعدة المحلية **1980** سجلاً تحتوي `password`. في islamic تقرر إيقاف التسجيل وترك السجلات القديمة كما هي.
- **log viewer**: arcanedev، والـ middleware من `ARCANEDEV_LOGVIEWER_MIDDLEWARE` غير الموجود في `.env` المحلي، أي بلا حماية محلياً على الأقل. في المشاريع السابقة استُبدل بـ opcodesio خلف دخول المشرف.
- **Telegram حقيقي في القاعدة المحلية**: جدول `settings` فيه token وchannel id فعليان، و`LogMessageJob` و`TelegramLoggerHandler` و`Handler` و`PdfZipService` ترسل إليه. يُعطَّل قبل أول تشغيل (تفريغ القيمة محلياً أو `Bus::fake` في كل اختبار)، وإلا وصلت أخطاء الاختبارات إلى قناة الإنتاج.
- **البريد من جدول `settings`**: `SettingServiceProvider` يضع إعدادات SMTP في `mail.mailers.smtp.*` (القيمة المحلية `smtp.office365.com`، أي حقيقية). Laravel 8 يقرأ هذه المفاتيح أصلاً، فيُتوقع ألا يتغير السلوك، لكن تُمنع أي رسالة حقيقية أثناء الاختبار، ويُتأكد بمقارنة `config()` كاملاً.
- **`dd(` في 15 controller**، منها ثلاثة في `UserOld/*` التي لا تشير إليها أي route. أي route GET ينتهي بـ `dd()` يوقف phpunit بصمت، فتُعرف قبل تشغيل `SmokeRoutesTest`.
- **لا يوجد class اسمه `Match`** هنا (الأسماء `QMatch` و`TMatch` و`StoryMatch`)، فمشكلة الكلمة المحجوزة في nonarab وreadingbenchmark غير موجودة. `t('Match')` نص فقط.
- ما ثبت في المشاريع السابقة ويُفحص هنا: `session.serialization` و`cache.serializable_classes` (وما يُحفظ في الكاش من objects)، prefix الكاش، Carbon 3، أسماء `.env` القديمة، `rehash_on_login`، مسار الـ local disk، ترميز CSV في Laravel Excel، الـ routes التي تشير إلى methods غير موجودة، ترتيب `sortBy` المستقر في PHP 8، صفحات الأخطاء.

## قرارات صاحب المشروع (22-10-2026، قبل أي تعديل)
عُرضت المشاكل الأمنية أعلاه ومعها اثنتان وُجدتا أثناء الفحص، وكلها موجودة على Laravel 8 وليست من الترقية:

| المشكلة | القرار |
| --- | --- |
| routes `migrate` و`view` و`command` و`cache` العامة في `routes/web.php` | **تُحذف** |
| ثغرة الـ IP في `api/update_settings` | **تُصلح** بـ `$request->ip()` مع `TrustProxies`، ومعها `TrustedIpApiTest`، في commit مستقل |
| hash كلمة المرور في activity log | **يُوقف التسجيل فقط**، والسجلات القديمة (1980) تبقى كما هي |
| log viewer | opcodesio **خلف دخول المشرف** (`manager` و`auth:manager`) |
| **جديد:** `GET students-cards-by-section?school_id=N` بدون دخول، يعرض كل طلاب أي مدرسة بالبريد وكلمة المرور الافتراضية `123456` ورمز QR للدخول (`General\UserController@cards`، ولا middleware عليه) | **يبقى كما هو ويُوثَّق** |
| **جديد:** `POST api/upload_image` بدون دخول، يرفع صوراً إلى `public/uploads/lesson_images` (بالامتدادات المسموحة فقط) | **يُوثَّق الآن ويُصلح لاحقاً** بعد معرفة من يستدعيه |

## أول الخطوات
1. `git fetch`، ثم فرع `upgrade/laravel-13` وtag لنقطة البداية.
2. تعطيل كل ما يرسل إلى الخارج (Telegram، البريد) قبل أول تشغيل.
3. تسجيل الـ baselines قبل أي تعديل: `routes-dump.php`، `config-dump.php`، `composer audit`، `composer show`، نتيجة الاختبارات، و`SmokeRoutesTest` بعد تكييفه ونسخه إلى `tests/Feature` وتشغيله بـ `SMOKE_RECORD=1`. يُستثنى منه `migrate` و`command` و`cache` و`view` و`seed` حتى لا تُشغَّل.
4. قراءة أقسام "دروس للمشروع التالي" في المراجع، من الأحدث إلى الأقدم، ثم ما وصل إليه nonarab.
