# أدوات مقارنة النسختين

هذه الملفات ليست جزءاً من التطبيق ولا تُشغَّل مع الاختبارات (ما عدا `SmokeRoutesTest.php`، ونسخته في `tests/Feature`). استُخدمت لمقارنة سلوك Laravel 8 بـ Laravel 13 على نفس قاعدة البيانات المحلية (`nonarabs`). أُعيدت كتابتها لهذا المشروع عن نسخة readingbenchmark، ولم يبقَ منها غير الفكرة وبعض أدوات المقارنة: الـ guards (manager وschool وteacher وsupervisor والطالب `web`)، والحسابات، والصفحات، والنماذج، والتصديرات، والاستيراد، واختبارات الدروس والقصص.

**قبل أي تشغيل:**
- الـ Exception Handler يرسل كل خطأ إلى Telegram عبر `LogMessageJob` (queue `sync`)، والـ token الحقيقي في `.env` وفي جدول `settings` وكقيمة افتراضية داخل `LogMessageJob` نفسه. `tests/TestCase.php` وكل اختبار هنا يعطّله بـ `Bus::fake([LogMessageJob::class])`.
- للأوامر خارج phpunit (السكربتات هنا و`artisan` و`php -S`) شُغّل PHP عبر wrapper يضبط `HTTP_PROXY`/`HTTPS_PROXY`/`ALL_PROXY` (بالحروف الكبيرة والصغيرة) على `http://127.0.0.1:9` فيفشل أي اتصال من Guzzle أو curl (Telegram وPusher وFCM وpdfservice وreCAPTCHA)، ويضبط `MAIL_MAILER=log` و`BROADCAST_DRIVER=log` و`TELEGRAM_BOT_TOKEN` بقيمة وهمية. البريد لا يمر عبر الـ proxy، لذلك `MAIL_MAILER=log` ضروري: `SettingServiceProvider` يضع خادم SMTP من جدول `settings`. لـ `config-dump.php` فقط يُشغَّل بـ `UPGRADE_KEEP_ENV=1` (الـ proxy وحده) حتى تظهر إعدادات البريد الحقيقية في المقارنة، وهو لا يرسل شيئاً.
- تشغيل واحد فقط في كل مرة على القاعدة، وبدون تعديل كود التطبيق أثناءه.
- الـ helper `t()` و`w()` يضيفان كل مفتاح ترجمة غير موجود إلى ملفات `resources/lang` (ليست في git). `run.sh` يحفظها قبل التشغيل ويعيدها بعده؛ وأي تشغيل آخر لـ phpunit (حتى `SmokeRoutesTest` وحده) يحتاج نفس الشيء يدوياً، وإلا ظهر المفتاح الجديد في صفحة "Text translation" في التشغيل التالي.

`SmokeRoutesTest` فيه اختيار الحسابات المشترك لكل الاختبارات، كلها مفعّلة ومعتمدة وبدون تغيير كلمة مرور معلّق في القاعدة المحلية، فلا يُعدَّل شيء لاختيارها: المشرف صاحب أكثر صلاحيات، والمدرسة 71 (أكبر مدرسة لمشرفيها معلمون مرتبطون؛ المدارس الأكبر فيها 4000 إلى 6000 طالب وتبطئ بعض الصفحات جداً)، ومعلمها صاحب أكثر طلاب، ومشرفها صاحب أكثر معلمين، وطالب ذلك المعلم صاحب أكثر اختبارات. وفيه قائمة routes الـ GET المستثناة (التي تكتب في القاعدة أو تنتهي بـ `dd()` أو تشير إلى controller غير موجود أو تبني ملفات كبيرة)، و`resetAuth()` الذي يعيد الـ guard الافتراضي إلى `web` بين طلبين (`actingAs()` يغيّره).

1. نسخة مرجعية من الكود القديم بجانب المشروع:
   ```
   git -c core.longpaths=true worktree add --detach ../nonarab-l8-ref 58a5414a
   ```
   (commit الـ baselines: كود التطبيق فيه مطابق لـ `before-l13`، وفيه `SmokeRoutesTest` و`TestCase` المعطّل لـ Telegram.) ثم نسخ `.env` و`vendor` (الحالي قبل الترقية؛ لا يوجد `composer.lock` قديم) و`resources/lang` (ليست في git) و`public/uploads` و`storage/app/public` إليها.
2. تشغيل كل اختبارات المقارنة على نسخة واحدة (تُنسخ مع `SmokeRoutesTest` إلى `tests/Feature` ثم تُشغّل بالترتيب، والنتائج في مجلد الـ snapshots):
   ```
   bash run.sh <wrapper php 7.4> ../nonarab-l8-ref <snapshots القديمة>
   bash run.sh <wrapper php 8.4> . <snapshots الجديدة>
   ```
   ويمكن تمرير أسماء اختبارات بعينها في النهاية (`Forms ParamPages`). كل ما يُكتب في قاعدة البيانات يُلغى (`DatabaseTransactions`)، والوقت مثبّت (`Carbon::setTestNow`) حتى لا يتغير شيء بين نسختين أو حول منتصف الليل. ملفات الاستيراد المرفوعة تُحذف في نهاية الاختبار، وملفات Excel المؤقتة في `storage/framework/laravel-excel` يحذفها `run.sh`. بعد الانتهاء تُحذف النسخ من `tests/Feature` (ما عدا `SmokeRoutesTest.php`).
3. المقارنة:
   - `php compare.php <مجلد القديم> <مجلد الجديد> --show=50` لكل الملفات. يستبعد ما يتغير في كل طلب: CSRF و`autocomplete="off"` الذي يضيفه `@csrf` في 13، والـ ids العشوائية (`Str::random`، `pw_password_<uniqid>`)، وbcrypt hash، والقيم المشفرة، وعمودا activitylog 4 الفارغان، واسم wrapper الأخطاء في Ignition، و"after:today" في jsvalidation، وصفحة الخطأ الافتراضية لـ 500.
   - `php routes-dump.php <مسار المشروع>` يطبع كل الـ routes مع الـ middleware بعد حلّها (والـ route التي يشير إلى controller غير موجود تُعلَّم بـ `error`)، و`php routes-compare.php before.json after.json` يقارن بعد مطابقة الـ middleware التي صارت من الإطار.
   - `php config-dump.php <مسار المشروع>` يطبع كل قيم `config()` بعد التشغيل (المفاتيح وكلمات المرور وروابط الـ webhook مخفية).
   - `php load-all.php <مسار المشروع>` يحمّل كل class في `app/` ويطبع التحذيرات والخصائص الديناميكية.

قبل المقارنة بين النسختين، يُشغَّل `run.sh` مرتين على النسخة القديمة ويُقارَن الناتجان، لمعرفة ما يتغير بدون أي ترقية (ترتيب أسئلة التوصيل والترتيب عشوائي في صفحات الاختبار).

| الملف | ما يغطيه |
| --- | --- |
| SmokeRoutesTest | حالة كل صفحة GET بدون parameters لكل guard وللزائر، مقابل `tests/smoke-baseline.json` |
| ResponseSnapshotTest | محتوى نفس الصفحات، كصفحة وكطلب datatables |
| ParamPagesSnapshotTest | صفحات بـ parameters وفلاتر للوحات الأربع والطالب والزائر: التعديل والعرض، المعاينات، التقارير، الشهادات، بطاقات الطلاب، صفحات الدروس والقصص للطالب والمعلم، الدخول كحساب آخر، تغيير اللغة، وطلبات datatables بفلاتر |
| AuthFlowSnapshotTest | الدخول والخروج والصفحة الثانية بعد الدخول والقفل وفرض تغيير كلمة المرور والحساب غير المفعّل لكل guard، ودخول المشرف كمدرسة/معلم/مشرف/طالب مرتين في نفس الجلسة، ودخول الطالب (والمؤرشف، و"تذكرني" بالكوكي وحده)، وسجل `login_sessions` |
| PasswordResetSnapshotTest | رسائل استعادة كلمة المرور (العنوان والسطور والرابط وHTML) وصفحة إعادة التعيين وإعادة التعيين نفسها للوحات الأربع والطالب |
| LogViewerAccessSnapshotTest | من يستطيع فتح `/log-viewer` |
| ExportSnapshotTest | تصديرات Excel للمشرف، وتصديرات General من اللوحات الأربع، وصفحات بطاقات الطلاب، خلية بخلية |
| ImportSnapshotTest | استيراد طلاب (xlsx وcsv عربي، صفوف صحيحة وخاطئة، بريد مستخدم في نفس المدرسة وفي غيرها، back_grade، ثم تحديث) واستيراد معلمين |
| StudentTestsSnapshotTest | الطالب يجيب اختبار درس واختبار قصة (صح وخطأ واختيار وتوصيل وترتيب، كاملة ونصف خاطئة)، والتتبع وإجابات التعلم، ثم ما يراه الطالب والمعلم |
| FormsSnapshotTest | نماذج الإضافة والتعديل الرئيسية (فارغة وخاطئة أيضاً) للوحات والطالب، والصفوف التي كتبتها، وسجل activity log، والبريد |
| TrustedIpApiTest | `api/update_settings` يرفض من يدّعي في ترويسة إعادة توجيه أنه عنوان موثوق |
| `session-check.php` | جلسة تكتبها نسخة وتقرأها الأخرى، بـ `php` و`json` |
| `session-http-check.sh` | دخول حقيقي عبر HTTP (`php -S`) على نسخة ثم متابعة نفس الجلسة على الأخرى، في الاتجاهين، ودخول الطالب بكوكي "تذكرني" وحده |
| `cookie-check.php` | كوكي الجلسة المشفر والقيم المشفرة بين النسختين |
| `cache-check.php` | ما يُكتب في الكاش بنسخة ويُقرأ بالأخرى (Setting وCollection ومصفوفة الإعدادات وبيانات لوحة الطالب)، وكاش الصلاحيات |
| `mail-render.php` | HTML رسالة استعادة كلمة المرور للمشرف |
