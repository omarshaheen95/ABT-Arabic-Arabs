# ترقية nonarab إلى Laravel 13 و PHP 8.4 (لم تبدأ بعد)

هذا المجلد منقول من مشاريع islamic وsocialuae وreadingbenchmark بعد إنهاء ترقياتها، ليكون نقطة البداية هنا. لم يُعدَّل أي شيء في كود nonarab حتى الآن.

| الملف | ما هو |
| --- | --- |
| `خطة تحديث مشاريع ABT.md` (في جذر المشروع) | الخطة العامة لكل المشاريع |
| `readingbenchmark-reference.md` | آخر ترقية وأكملها توثيقاً (من Laravel 7). فيها ثغرة الـ IP، وclass `Match`، ومقارنة `config()`، وتعطيل Telegram، و"دروس للمشروع التالي" |
| `socialuae-reference.md` | الترقية الثانية (من Laravel 7). فيها `session.serialization` و`cache.serializable_classes` وصفحات الأخطاء وقوالب البريد، ودروسها |
| `islamic-reference.md` | الترقية الأولى (من Laravel 6)، ودروسها، وفي آخرها ما وجدته الترقيتان التاليتان ولم يُطبَّق فيها |
| `harness/` | أدوات المقارنة بنسخة readingbenchmark (الأحدث)، ومعها `cache-check.php` من islamic و`mobile-detect-check.php` من socialuae و`TrustedIpApiTest.php`. كلها مكتوبة على models وguards ومسارات تلك المشاريع، وتحتاج إعادة كتابة كبيرة هنا (انظر أدناه) |

## nonarab مختلف عن المشاريع الثلاثة
- **Laravel 8.83.29** (المثبت في vendor)، وPHP `^7.3`. أقرب نقطة بداية من المشاريع السابقة، لكن الحزم أكثر وأحدث.
- **تطبيق مختلف تماماً**: دروس وقصص وألعاب وفيديو وواجبات وشهادات وباقات ومدفوعات، لا اختبارات تقييم. لذلك أدوات المقارنة (الصفحات والنماذج والتصديرات والتصحيح) تُكتب من جديد على مسارات هذا المشروع، ولا تُنقل إلا فكرتها.
- **الـ guards**: `manager` و`school` و`teacher` و`supervisor` و`web` (الطلاب، model `User`). الـ models في `app/Models`، ومنها `Permission` و`Role` خاصة بالمشروع.
- **قاعدتا بيانات**: `nonarabs`، واتصال ثانٍ `DB_DATABASE_ABT=abt`.
- `composer.lock` **ليس** في git (كما في socialuae)، فيُحفظ `composer show` كـ baseline، وتُبنى النسخة المرجعية من `vendor` الحالي.
- `resources/lang` ليست في git (ما عدا `auth_log.php`)، وتُنسخ عند النشر.
- `resources/views/vendor/mail` منشورة أصلاً (قوالب البريد للمشروع)، ولا يوجد `resources/views/errors`.
- الـ scheduler في `app/Console/Kernel.php` (مهمتان).
- الحزم الإضافية عن المشاريع السابقة، ولكل منها قرار ترقية مستقل: astrotomic/laravel-translatable، spatie/laravel-translatable 4، spatie/laravel-backup 6، spatie/laravel-permission **3** (أقدم من السابقة)، intervention/image 2، php-ffmpeg 0.19، propaganistas/laravel-phone 4، pusher 4، laravel/ui 3، guzzle 7 أصلاً.

## ما رُصد قبل البدء ويجب فحصه
- **class اسمه `Match`** (`app/Models/Match.php`): `match` كلمة محجوزة منذ PHP 8.0 فلا يُحمَّل أصلاً. يُستخدم في controllers الدروس والقصص والتدريب. في readingbenchmark صار `QuestionMatch` على نفس الجدول. ابحث أيضاً عن أسماء محجوزة أخرى.
- **ثغرة الـ IP نفسها**: `getRealIpAddress()` في `app/Helpers/helpers.php` تقرأ `X-Forwarded-For` أولاً، و`isTrustIpAddress()` تحمي `api/update_settings`. أُصلحت في readingbenchmark وislamic بـ `$request->ip()`. تُعرض على صاحب المشروع قبل الإصلاح.
- **log viewer**: arcanedev، والـ middleware من `ARCANEDEV_LOGVIEWER_MIDDLEWARE` غير الموجود في `.env` المحلي، أي بدون حماية محلياً. في المشاريع الثلاثة كان بلا حماية واستُبدل بـ opcodesio خلف دخول المشرف.
- **Telegram**: `LogMessageJob` و`TelegramLoggerHandler` و`TelegramChannel` موجودة. تُعطَّل في كل اختبار (`Bus::fake`)، ويُبحث في جدول `settings` عن token حقيقي قبل أي تشغيل.
- **البريد من جدول `settings`**: `SettingServiceProvider` يضع إعدادات SMTP في `mail.mailers.smtp.*`. Laravel 8 يقرأ هذه المفاتيح أصلاً (بخلاف islamic على 6)، فيُتوقع ألا يتغير شيء، لكن يُتأكد بمقارنة `config()` كاملاً.
- **`dd(` في 11 controller**: أي route GET ينتهي بـ `dd()` يوقف phpunit بصمت، فتُعرف قبل تشغيل SmokeRoutesTest.
- `routes/manager.php` يشغّل `Artisan::call('db:seed ...')` من route.
- ما ثبت في المشاريع السابقة ويُفحص هنا: `session.serialization`، `cache.serializable_classes` (وما يُحفظ في الكاش من objects)، prefix الكاش، Carbon 3، أسماء `.env` القديمة، `rehash_on_login`، مسار الـ local disk، `password` في activity log، ترميز CSV في Laravel Excel، الـ routes التي تشير إلى methods غير موجودة، وترتيب `sortBy` المستقر في PHP 8.
- ما غيّره Laravel 7 و8 أصلاً (التواريخ في JSON، `getOriginal()`) لا يحتاج معالجة غالباً، لكن المقارنة هي التي تحكم.

## أول الخطوات
1. `git fetch`، ثم فرع جديد وtag لنقطة البداية.
2. تعطيل كل ما يرسل إلى الخارج (Telegram، بريد، Pusher، webhooks) قبل أول تشغيل.
3. تسجيل الـ baselines قبل أي تعديل: `routes-dump.php`، `config-dump.php`، `composer audit`، `composer show`، نتيجة اختبارات المشروع، و`SmokeRoutesTest` بعد تكييفه ونسخه إلى `tests/Feature` وتشغيله بـ `SMOKE_RECORD=1`.
4. قراءة أقسام "دروس للمشروع التالي" في المراجع الثلاثة، من الأحدث إلى الأقدم.
