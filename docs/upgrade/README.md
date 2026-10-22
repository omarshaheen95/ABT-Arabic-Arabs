# ترقية arabic-arabs إلى Laravel 13 و PHP 8.4

بتاريخ 22-10-2026 · الفرع `upgrade/laravel-13` · نقطة البداية: الـ tag `before-l13` (الـ commit `ba0ca796`، مطابق لـ `origin/master`) · **لم يُرفع شيء إلى GitHub**

نُفِّذت الترقية بحسب "خطة تحديث مشاريع ABT" (في جذر المشروع): هيكل Laravel 13 جديد داخل نفس الـ repo، مع إبقاء كود المشروع كما هو، ثم مقارنة سلوك النسخة الجديدة بالقديمة على نفس قاعدة البيانات المحلية (`arabicarabs`). ترقية nonarab (فرع `upgrade/laravel-13` هناك، الـ commit `6489bec8`) كانت نقطة البداية لأنه أقرب المشاريع، لكن كل ما أُخذ منه فُحص هنا من جديد، ولم يُعدَّل فيه شيء.

| | قبل | بعد |
| --- | --- | --- |
| Laravel | 8.83.29 | 13.38.0 |
| PHP | `^7.3\|^8.0` (composer) / 7.4 محلياً | `^8.4` |
| PHPUnit | 9.6 | 12.5 |
| spatie/laravel-medialibrary | 7.20 | 11.23 |
| `composer audit` | 70 ثغرة معروفة في 16 حزمة | لا شيء |

| الـ commit | ما فيه |
| --- | --- |
| `a3b1eb5d` | الخطة وتوثيق الترقيات السابقة |
| `f0d1f9b3` | الـ baselines قبل أي تعديل (`routes-before.json` و`config-before.json` و`composer-audit-before.txt` و`composer-show-before.txt` و`tests-before.txt` و`tests/smoke-baseline.json`)، و`tests/TestCase.php` يعطّل `LogMessageJob` |
| `870870fc` | أدوات المقارنة بعد تكييفها لهذا المشروع (`harness/`)، وقرارات صاحب المشروع |
| `4a6a4a7a` | الترقية نفسها |
| `d0edf601` | تحويل الـ routes النصية إلى `[Controller::class, 'method']` |
| `df807145` | حذف routes الـ artisan العامة |
| `c501c1e9` | إصلاح فحص الـ IP في `api/update_settings`، و`TrustedIpApiTest` |
| `b807e9c9` | إيقاف حفظ hash كلمة المرور في activity log |
| `346df7c5` | هذا التوثيق ونتائج المقارنة |
| (آخر commit) | إيقاف حفظ `remember_token` في activity log |

## قرارات صاحب المشروع (قبل أي تعديل)
عُرضت المشاكل الأمنية التالية على صاحب المشروع (كلها قبل الترقية ما عدا الأخيرة)، وكلها موجودة على Laravel 8 وليست منها:

| المشكلة | القرار | التنفيذ |
| --- | --- | --- |
| `GET migrate` و`view` و`command` (يشغّل `schedule:work` الذي لا ينتهي) و`cache` في `routes/web.php` بلا أي حماية | تُحذف | `df807145` |
| `getRealIpAddress()` تثق بـ `X-Forwarded-For` وأخواتها، فأي شخص يغيّر جدول `settings` (ومنه SMTP وtoken الـ Telegram) عبر `POST api/update_settings` | تُصلح | `c501c1e9` (انظر "ثغرة أُصلحت") |
| hash كلمة المرور في activity log (1980 سجلاً محلياً) | يُوقف التسجيل فقط، والسجلات القديمة تبقى | `b807e9c9` |
| **وُجد بعد الترقية:** `remember_token` (توكن "تذكرني") يُسجَّل في activity log بنفس الطريقة (1933 سجلاً محلياً؛ يتغير مع كل دخول بـ "تذكرني" وكل خروج) | يُوقف التسجيل، والسجلات القديمة تبقى | commit مستقل بعد التوثيق |
| log viewer (arcanedev) بلا middleware | opcodesio خلف دخول المشرف | ضمن `4a6a4a7a` |
| **وُجد أثناء الفحص:** `GET students-cards-by-section?school_id=N` بلا دخول يعرض كل طلاب أي مدرسة بالبريد وكلمة المرور الافتراضية `123456` ورمز QR للدخول (`General\UserController@cards`) | يبقى كما هو ويُوثَّق | لم يُلمس |
| **وُجد أثناء الفحص:** `POST api/upload_image` بلا دخول يرفع صوراً إلى `public/uploads/lesson_images` (بالامتدادات المسموحة فقط) | يُوثَّق ويُصلح لاحقاً بعد معرفة من يستدعيه | لم يُلمس |

## ما الذي تغيّر

### الهيكل
- `bootstrap/app.php` يحل محل `app/Http/Kernel.php` و`app/Console/Kernel.php` و`RouteServiceProvider`. **الترتيب كما كان**: الـ panels الأربع (manager، school، supervisor، teacher) أولاً، ثم api، ثم web، وكل منها بنفس الـ prefix والاسم والـ middleware بنفس الترتيب. الترتيب مهم هنا لأن `web.php` فيه `{type}/certificates` الذي يطابق روابط الـ panels لو سُجّل قبلها (فُحص بـ `harness/routes-order.php`: 793 route بنفس الترتيب).
- مجموعة `web` هي مجموعة الإطار كما كانت: **بدون `AuthenticateSession`** (كان معلّقاً في Kernel القديم، بخلاف nonarab). مجموعة `api` بـ `throttle:api` ونفس الـ rate limiter (60 في الدقيقة لكل مستخدم أو IP). الـ aliases بنفس الأسماء (`manager` و`school.guest` و`local` و`checkIfActive` و`force.password` و`session.password` و`role`/`permission` من `Spatie\Permission\Middleware` ...). `old_password` مستثنى من `TrimStrings` كما كان.
- `bootstrap/providers.php` فيه `AppServiceProvider` و`RepositoryServiceProvider` و`SettingServiceProvider`. ما كان في `EventServiceProvider` (الـ subscriber والـ observers الأربعة) صار في `AppServiceProvider`، واكتشاف الـ listeners مطفأ حتى لا يُسجَّل الـ subscriber مرتين.
- الـ Exception Handler القديم باقٍ (يرسل إلى Telegram ويشكّل أخطاء JSON)، مربوط بعد `->create()`.
- حُذفت نسخ التطبيق من middleware الإطار (`EncryptCookies` و`VerifyCsrfToken` و`TrimStrings` و`TrustProxies` و`TrustHosts` و`PreventRequestsDuringMaintenance` و`RedirectIfAuthenticated`) لأنها لم تكن معدّلة (إلا `old_password`)، و`server.php` و`composer_.lock`.
- الـ scheduler (`login-sessions:prune` يومياً 03:00 و`user:check-expire` مرتين) في `withSchedule()`، و`schedule:list` يعطي نفس المهمتين بنفس المواعيد.
- كل الـ routes بصيغة `[Controller::class, 'method']` (255 action، حُوّلت بالـ tokenizer)، ولا namespace للـ controllers. `routes-dump.php` و`routes-order.php` بعد التحويل مطابقان لما قبله، و`route:cache` يعمل.

### medialibrary 7 ← 11 (أكبر مخاطرة هنا)
- الـ namespaces في 7 models (`Lesson` و`Question` و`TQuestion` و`QMatch` و`TMatch` و`Option` و`TOption`) والـ Handler: `Spatie\MediaLibrary\HasMedia` و`InteractsWithMedia` و`MediaCollections\Models\Media`.
- `config/media-library.php` بقيم النسخة 7: الحجم الأقصى 100MB (الافتراضي الجديد 10MB، وفيديوهات الدروس تصل إلى 50MB)، نفس الـ optimizers والـ image generators، والتحويلات تُنفَّذ فوراً كما كانت (`queue_conversions_after_database_commit = false`)، وتسلسل الـ media كـ Eloquent collection عادية.
- **الروابط بقيت نسبية**: النسخة 11 تبني الرابط من `APP_URL` (`http://localhost/storage/2209/x.jpg`)، والنسخة 7 كانت تحذف جذر الموقع الحالي منه (`/storage/2209/x.jpg`). المنصة تعمل على أكثر من domain (`sysDomain()`)، فالرابط المطلق كان سيوجّه صفحات الـ domains الأخرى إلى `APP_URL`. `App\Support\MediaLibrary\LocalUrlGenerator` يعيد طريقة النسخة 7 للأقراص المحلية. **الرابط والمسار ورابط التحويل `minImage` ومساره وحالته متطابقة للـ 6793 ملفاً كلها** بين النسختين.
- migration `2026_10_22_130000` تضيف إلى `media` الأعمدة `uuid` و`conversions_disk` و`generated_conversions`، وتملأ الأخيرين من الصفوف (1422 صورة لها `minImage`). التحويلات **تُنسخ** من `custom_properties` ولا تُنقل، والأعمدة nullable، حتى يبقى كود Laravel 8 يعمل على الجدول عند الرجوع. `down()` يعيد الجدول كما كان بالضبط (فُحص بـ `CHECKSUM TABLE`). الملفات القديمة يبقى `uuid` فيها null (لا يستخدمه إلا medialibrary-pro).
- تبقى للاختبار اليدوي: رفع صورة درس (تحويل `minImage` يُولَّد الآن بـ spatie/image 3، وقد يختلف قليلاً في الأبعاد عن السابق) وصوت وفيديو، وحذفها.

### الحزم
| الحزمة | القرار |
| --- | --- |
| spatie/laravel-permission 5.11 | 8.3. الـ middleware انتقل إلى `Spatie\Permission\Middleware`. كاش الصلاحيات القديم يُقرأ، لكن `permission:cache-reset` في النشر احتياطاً |
| spatie/laravel-activitylog 3.17 | 4.12. `LogsActivityTrait` صار فيه `getActivitylogOptions()` بنفس الحقول والخيارات (كما في nonarab)، وmigration تضيف `event` و`batch_uuid` |
| spatie/laravel-medialibrary 7.20 | 11.23 (أعلاه) |
| laravel/sanctum 2.15 | 4.3 (المستخدم فقط `api/user`)، مع config جديد بنفس القيم |
| yajra/laravel-datatables-oracle 9 | 13.3. الاستدعاءان الثابتان `DataTables::eloquent()` (في `LessonAssignmentRepository` و`StoryAssignmentRepository`) صارا `DataTables::make()` كبقية المشروع: `eloquent()` لم تعد static، فكانت صفحات الواجبات ترجع 500 (ظهر في المقارنة) |
| maatwebsite/excel 3.1.56 | 3.1.70، والـ CSV يُقرأ UTF-8 بفاصل يُكتشف تلقائياً كما كان، والملفات المؤقتة في `storage/framework/cache/laravel-excel` كما كانت |
| propaganistas/laravel-phone 4 | 6.1. `PhoneNumber::make()->ofCountry()` (حُذفت) صارت `new PhoneNumber($number, $country)` في 3 أماكن، بنفس الناتج |
| arcanedev/log-viewer 8 | حُذفت (لا نسخة لـ 13) ومكانها opcodesio/log-viewer خلف `manager` و`auth:manager`، وملفات الواجهة منشورة في `public/vendor/log-viewer` |
| hesto/multi-auth | حُذفت، والـ trait الوحيد المستخدم (`LogsoutGuard`) نُقل كما هو إلى `app/Traits` |
| laravel/helpers | حُذفت. الاستدعاءان (`array_get` في الـ Handler و`str_before` في `user/layout`) صارا `Arr::get` و`Str::before` |
| fruitcake/laravel-cors, facade/ignition, laravel/sail | حُذفت. `config/cors.php` كما كان |
| laravel/ui 3، jsvalidation، simple-qrcode، cascade-soft-deletes | أحدث نسخة متوافقة. ملف `public/vendor/jsvalidation` المنشور خاص بالمشروع (يختلف عن الحزمتين) فبقي كما هو |
| barryvdh/laravel-debugbar | 4.4 في `require-dev` |

`app/Helpers/helpers.php` كان في `autoload-dev` فقط (أي لا يُحمَّل مع `composer install --no-dev`)، وصار في `autoload`، كما في nonarab. `composer.lock` محدّث.

### سلوك أبقيناه كما كان في Laravel 8 عن قصد
- **لا يخرج المستخدمون عند النشر**: `session.serialization` بقيت `php`. جلسة كتبها 8 تُقرأ في 13 والعكس، وبـ `json` كان سيضيع الدخول وتوكن CSRF (`harness/session-check.php`). وعبر HTTP حقيقي (`session-http-check.sh`): دخول المشرف والطالب على نسخة ثم متابعة نفس الجلسة على الأخرى، في الاتجاهين، ودخول الطالب بكوكي "تذكرني" وحده، كلها 200. الكوكيز والقيم المشفرة تُفك في الاتجاهين.
- **`ValidateSessionPassword`** يقبل أيضاً الـ HMAC الذي يكتبه `SessionGuard::login()` منذ Laravel 11 تحت نفس المفتاح (من nonarab، والملف مطابق له). بدونه يخرج مستخدم اللوحات في الصفحة الثانية بعد الدخول.
- **الكاش**: `cache.serializable_classes` بقيت `true`: مفتاح `settings` يحفظ أحياناً model `Setting` أو Collection، و`UserDataCacheService` يحفظ مصفوفات فيها models. بـ `false` تصير `__PHP_Incomplete_Class` (`harness/cache-check.php`، في الاتجاهين). الـ prefix كما كان.
- **صفحات الأخطاء والبريد**: نُسخت من Laravel 8 الملفات التي تغيّرت في 13: `errors/layout` و`minimal`، وقوالب `vendor/mail` و`vendor/notifications/email` (لم تكن منشورة في المشروع)، وعنوان رسالة استعادة كلمة مرور الطالب. رسائل الاستعادة للـ guards الخمسة مطابقة.
- **Carbon 3**: استدعاءات `diffIn*` (12) صارت `(int) ...(..., true)`، والتي كانت بإشارة (`false`) بقيت بإشارتها.
- **ملفات `.env` الحالية تعمل كما هي**، و`config()` كاملاً مطابق بعد التشغيل ما عدا مفاتيح جديدة في 13، ومفاتيح حزم حُذفت، وإعدادات debugbar (dev فقط). إعدادات SMTP من جدول `settings` كما كانت.
- bcrypt بلا فحص الخوارزمية ولا إعادة hash عند الدخول، ومسار الـ local disk `storage/app`، والـ pagination على Bootstrap.

### PHP 8.4
- `$dates` صارت `$casts` في `User` و`UserAssignment` و`UserStoryAssignment`.
- إعلان `$statusCode` في الـ Handler و`$log_errors` في `UserImport`، وجعل الـ nullable صريحاً (`LoginSession::scopeFilter` و`registerMediaConversions`)، و`redirectWith()` في `Controller` (المعامل الذي له قيمة افتراضية قبل معامل إلزامي).
- كل الـ 329 class في `app/` تُحمَّل على PHP 8.4 بلا تحذير (`harness/load-all.php`)، ما عدا `StoryAssessmentRequest` الذي namespace فيه خطأ أصلاً ولا يستخدمه شيء (نفس النتيجة على Laravel 8).

## ثغرة أُصلحت بعد الترقية
`isTrustIpAddress()` صار يقارن `$request->ip()` بقائمة `config('app.trusted_ips')` (مقارنة صارمة)، و`getRealIpAddress()` حُذفت (لا مستخدم آخر لها). `$request->ip()` يمر على `TrustProxies`، ولا proxies موثوقة هنا، فهو عنوان الاتصال الفعلي. `tests/Feature/TrustedIpApiTest.php`: عنوان غير موثوق يدّعي `127.0.0.1` في كل واحدة من الترويسات الخمس يُرفض بـ 401 ولا يتغير شيء. **على الكود القديم نجحت الحالات الخمس كلها في تغيير الإعداد.**

**قبل النشر:** إذا كان السيرفر خلف proxy أو load balancer أو Cloudflare، فـ `$request->ip()` هو عنوان الـ proxy، وسيُرفض المتصلون الموثوقون حتى يُضاف عنوانه بـ `$middleware->trustProxies(at: [...])` في `bootstrap/app.php`. لا يُضاف `'*'`، فهو يعيد الثغرة نفسها.

## كيف تأكدنا أن السلوك لم يتغير
كل المقارنات على نفس القاعدة المحلية، مرة على نسخة Laravel 8 (git worktree من commit الـ baselines في `../arabic-arabs-l8-ref`، وكود التطبيق فيه مطابق لـ `before-l13`) ومرة على Laravel 13، وكل كتابة داخل transaction تُلغى. الأدوات في `harness/` (انظر `harness/README.md`). تشغيلان على Laravel 8 تطابقا إلا في ids الصفوف الجديدة وترتيب أسئلة القصة العشوائي.

**قبل أي تشغيل** عُطّل كل ما يرسل إلى الخارج: `tests/TestCase.php` وكل اختبار يعطّل `LogMessageJob` بـ `Bus::fake` (وتأكدنا أن صفحة 500 ترسله فعلاً وأن الـ fake يلتقطه)، وكل أمر PHP شُغّل عبر wrapper يوجّه أي HTTP إلى `127.0.0.1:9` ويضع `MAIL_MAILER=log` وtoken Telegram وهمياً (`LogMessageJob` نفسه فيه token حقيقي كقيمة افتراضية). لم تُستدعَ routes `migrate` و`cache` و`view` و`command` و`seed` في أي اختبار.

| الفحص | النتيجة |
| --- | --- |
| قائمة الـ routes وترتيب تسجيلها | 793 route: نفس الرابط والاسم والـ action والـ middleware وترتيبه، ونفس ترتيب التسجيل. الفرق الوحيد اسم `sanctum.csrf-cookie`، ثم حذف الأربعة العامة (789) |
| `config()` كاملاً بعد التشغيل | نفس القيم ما عدا المذكور أعلاه |
| 1203 لقطة: كل صفحات GET لكل guard وللزائر كصفحة وكطلب datatables، وصفحات بـ parameters (الدروس التي تعرض صوراً وصوتاً وفيديو من medialibrary، المنهج للمعلم، التصحيح، الشهادات، الدخول كحساب آخر، تغيير اللغة)، و64 تصديراً خلية بخلية، والاستيراد، والنماذج، والدخول، واستعادة كلمة المرور، والطالب يحل اختبار درس وقصة | 1170 مطابقة و33 مختلفة، كلها مشروحة أدناه: إزالة `password` من سجلات activity log (المقصود)، والفروق الأربعة "المتروكة عن قصد"، وفروق لا تغيّر السلوك (ids الصفوف الجديدة، ترتيب أسئلة القصة العشوائي، نص الأخطاء، صفحة "Text translation" بنفس المفاتيح وترتيب آخر) |
| الدخول والخروج والقفل وفرض تغيير كلمة المرور والحساب غير المفعّل لكل guard، والدخول كحساب آخر، و`login_sessions` | مطابق |
| استيراد طلاب (xlsx وcsv بنص عربي، صفوف صحيحة وخاطئة، تحديث، back_grade) ومعلمين | مطابق |
| الطالب يحل اختبار درس وقصة (صح وخطأ، كامل ونصف خاطئ) والتتبع، ثم صفحاته وصفحات المعلم | مطابق (نفس الدرجات والنتيجة) |
| روابط ومسارات الـ 6793 ملف media | متطابقة |
| الجلسات والكوكيز والكاش بين النسختين | تُقرأ في الاتجاهين |
| `migrate` ثم `rollback` ثم `migrate` | الجداول بعد `rollback` مطابقة لما قبل (`CHECKSUM TABLE`) |
| اختبارات المشروع (8) و`SmokeRoutesTest` (6) و`TrustedIpApiTest` (7) | تنجح (`tests-before.txt` / `tests-after.txt`)، والـ smoke بنفس صفحات الـ 500 الموجودة أصلاً |

**فروق ظهرت وتُركت عن قصد** (كلها تصلح خللاً كان قبل الترقية):
- **قائمة المشرفين**: `ManagerController@index` يحمّل `login_sessions` بـ `latest()->limit(1)`. في Laravel 8 كان الـ limit على الاستعلام كله فلا يحصل على آخر دخول إلا مشرف واحد على الأكثر (ظهر "-" للجميع)، ومنذ Laravel 11 يُطبَّق لكل مشرف، فصارت القائمة تعرض آخر دخول فعلي لكل مشرف.
- **رسائل التحقق بالإنجليزية**: لا يوجد في المشروع `resources/lang/en/validation.php`، فكان Laravel 8 يعرض المفتاح نفسه (`validation.required`) في نماذج الطالب، وLaravel 13 يحمل ترجمات إنجليزية داخله فيعرض "The email field is required.". العربية كما كانت (المفاتيح تظهر كما هي إن لم تُترجم).
- **log viewer**: الزائر والمدرسة والمعلم والمشرف والطالب يُوجَّهون إلى دخول المشرف (كانوا يفتحونه).
- `manager/wrong_audio_lessons` (أداة تشخيص للمشرف ترجع صفوف media كـ JSON): صار فيها `original_url` و`preview_url`، و`generated_conversions` مصفوفة.

فروق ليست تغييراً في السلوك: نص رسالة الـ `ValidationException` الداخلية، ونص أخطاء PHP 8 داخل استجابات datatables التي ترجع خطأً أصلاً (`lessons_tests` و`stories_tests` لاختبار طالبه محذوف)، وصفحة الـ 500 والـ 404 في وضع debug.

## النشر
1. PHP **8.4.27 أو أحدث** (8.4.7 الموجودة محلياً مصابة بـ php2shell). الـ extensions: `pdo_mysql` و`mbstring` و`gd` و`zip` و`intl` و`bcmath` و`fileinfo` و`openssl` و`exif` (medialibrary 11).
2. `.env` الحالي يعمل كما هو. `resources/lang` ليست في git (ما عدا `auth_log.php`)، فتُنسخ من السيرفر الحالي، ومعها `public/uploads` و`storage/app/public` (ملفات media) ورابط `public/storage`.
3. في مجلد جديد بجانب القديم:
   ```
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan permission:cache-reset
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
   الـ migrations الجديدة اثنتان فقط (`migrate --pretend` لم يُظهر غيرهما): عمودان nullable في `activity_log`، وثلاثة في `media` مع ملئها. كلتاهما مطبّقتان الآن على `arabicarabs` المحلية، ونسخة Laravel 8 تعمل معهما.
4. إذا كان السيرفر خلف proxy: `trustProxies` (أعلاه) قبل فتح الموقع.
5. الـ cron (`schedule:run`) كما هو.
6. الرجوع: الـ symlink إلى المجلد القديم، ثم `php artisan migrate:rollback --step=2` من المجلد الجديد إن لزم (الجداول تعود كما كانت)، و`permission:cache-reset`. الجلسات تبقى في الاتجاهين. ملفات media التي رُفعت على 13 تبقى صالحة على 8 (روابطها بنفس الصيغة).

## مشاكل كانت موجودة قبل الترقية (لم نلمسها)
- `students-cards-by-section` و`api/upload_image` بلا دخول (أعلاه، بقرار صاحب المشروع).
- `user/{id}/report` يعرض تقرير أي طالب برقمه لأي زائر (middleware `web` و`local` فقط).
- routes بطلب GET تغيّر البيانات خلف دخول المشرف: `seed` و`give_roles` و`give_all_permissions/{guard}` و`remove_space` و`copy-teacher-data` و`copy_lessons`، و`get_marks` و`correct_tests` تنتهي بـ `dd()`.
- 3 routes تشير إلى methods غير موجودة: `Manager\LessonController@export` و`General\UserController@studentsImportFileCards` و`General\UserController@updateLearningYears`. وصفحات `create` لـ `lessons_tests` و`stories_tests` و`stories_records` و`login_sessions` ترجع 500 (Route::resource لـ methods غير موجودة)، و`my_students` ترجع 500 للوحات، و`student/password/reset/{token}` ترجع 500.
- الـ Exception Handler يرسل كل خطأ إلى Telegram، و`LogMessageJob` فيه token حقيقي كقيمة افتراضية في الكود.
- `helpers.php` فيه server key لـ FCM مكتوب في الكود، و`send_push_to_pusher()` تستخدم `Pusher\Pusher` غير المثبتة (لا يستدعيها شيء).
- `ActivityLogController@show` يستخدم `\GuzzleHttp\json_decode` (سيُحذف في Guzzle 8)، و`app/Models/Activity.php` غير مستخدم.
- إعدادات composer العامة على هذا الجهاز: `disable-tls = true` و`secure-http = false`.

## متابعات مقترحة
- `trustProxies` إن كان السيرفر خلف proxy (قبل النشر).
- قرار بشأن `students-cards-by-section`، و`api/upload_image` (من يستدعيه؟)، و`user/{id}/report`.
- إصلاح routes الـ GET التي تغيّر البيانات أو تنتهي بـ `dd()`، والـ routes التي تشير إلى methods غير موجودة (`->only([...])` على `Route::resource`).
- التأكد من نسخة MySQL على السيرفر (المحلي 8.0.30).
- الانتقال من Laravel Mix إلى Vite في خطوة منفصلة.

## دروس للمشروع التالي
تضاف إلى ما في `nonarab` و`readingbenchmark-reference.md` و`socialuae-reference.md` و`islamic-reference.md`.

### قبل البدء
- الحسابات المحلية: كل المشرفين وأغلب المعلمين عليهم تغيير كلمة مرور إجباري، والمدرسة صاحبة أكبر عدد من روابط المشرفين كل طلابها مؤرشفون. اختيار المدرسة بعدد اختبارات الطلاب غير المؤرشفين أعطى بيانات أغنى، والمشرف من مدرسة أخرى. رفع القيود يكون داخل الـ transaction (`unlockAccounts()`).
- `git rm` يضع الحذف في الـ index: `git add <ملفات>` ثم commit بعدها يلتقط الحذف معه. التحقق بـ `git diff --cached --stat` قبل كل commit.
- فرق في صفحة قد يكون أثراً لخطأ في صفحة فُتحت قبلها في نفس التشغيل: بطاقات طلاب المعلم ظهرت بترتيب مختلف، وكان السبب صفحة الواجبات التي رجعت 500 قبلها؛ بعد إصلاحها تطابقت. يُصلح أول فرق حقيقي ثم يُعاد التشغيل قبل البحث في البقية.

### Laravel 13 والحزم
- **medialibrary 11 يبني الروابط من `APP_URL`** بدل الرابط النسبي في 7. مقارنة `getUrl()`/`getPath()` لكل صفوف `media` بين النسختين (سكربت بسيط) أسرع وأدق من مقارنة الصفحات.
- **`limit()` داخل eager loading** صار يُطبَّق لكل parent منذ Laravel 11: يظهر كبيانات "زائدة" في المقارنة.
- **استدعاءات static لـ methods لم تعد static** (`DataTables::eloquent()`) لا تظهر عند تحميل الـ classes، بل عند فتح الصفحة: المقارنة بالطلبات هي التي كشفتها.
- Laravel 13 يحمل ملفات ترجمة إنجليزية، فمفاتيح `validation.*` التي كانت تظهر كما هي صارت جملاً.
- مفاتيح `t()` التي تُضاف أثناء التشغيل تجعل صفحة "Text translation" تعتمد على ما فُتح قبلها: إعادة تشغيل جزء من الاختبارات تُظهر فرقاً فيها؛ المقارنة تكون بين تشغيلين كاملين.
- ملف migration يحتاج أن يبقى الجدول صالحاً لكود النسخة القديمة (أعمدة nullable، ونسخ البيانات لا نقلها) حتى يكون الرجوع ممكناً.

### التعديلات الآلية
- كما في المشاريع السابقة: أداة Bash تفسد الـ backslash في heredoc و`sed`، فكل استبدال فيه backslash كُتب في ملف JSON بأداة الكتابة ونُفّذ بسكربت يرفض الكتابة إن لم يجد النص بالعدد المتوقع ويحفظ نهايات الأسطر.
- `stripcslashes` لا تقرأ النصوص بعلامة مفردة كما يقرؤها PHP (`'General\UserController'` صار `GeneralUserController`): تحويل الـ routes بالـ tokenizer يحتاج قارئاً للنص كما يفعله PHP.

## قائمة الاختبار اليدوي
بحسب الخطة: الدخول والخروج لكل guard (ومنه دخول المشرف كمدرسة ومعلم ومشرف وطالب)، الصلاحيات، الجداول (بحث وترتيب وتقسيم صفحات)، استيراد ملف طلاب xlsx وcsv وتصدير Excel، بطاقات الطلاب وQR والشهادات والتقارير، ملفات PDF المضغوطة (`pdfReports`، تتصل بخدمة خارجية فلم تُختبر)، **رفع صورة وصوت وفيديو لدرس وصور الأسئلة والتوصيل وحذفها، وعرض الصور والصوت للطالب على كل domain يعمل عليه الموقع**، تسجيل صوت الطالب للقصة، الترجمة واتجاه الصفحة، log viewer بحساب مشرف، الـ scheduler، رسالة استعادة كلمة مرور حقيقية عبر SMTP، `api/update_settings` من السيرفر الموثوق، ومراقبة `storage/logs` يوماً كاملاً على نسخة بيانات الإنتاج.

## الملفات في هذا المجلد
| الملف | ما هو |
| --- | --- |
| `routes-before.json` / `routes-after.json` | ناتج `harness/routes-dump.php` قبل وبعد (بعد حذف routes الـ artisan) |
| `config-before.json` / `config-after.json` | ناتج `harness/config-dump.php` (كلمات المرور والمفاتيح مخفية) |
| `composer-audit-before.txt` / `composer-audit-after.txt` | ناتج `composer audit` |
| `composer-show-before.txt` / `composer-show-after.txt` | نسخ الحزم المثبتة |
| `tests-before.txt` / `tests-after.txt` | اختبارات المشروع ونتيجتها على Laravel 8 ثم 13 |
| `nonarab-reference.md` وبقية `*-reference.md` | توثيق المشاريع السابقة، والأرقام فيها تخصها |
| `harness/` | أدوات المقارنة (انظر `harness/README.md`) |
