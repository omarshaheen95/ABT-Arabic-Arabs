# أدوات مقارنة النسختين

هذه الملفات ليست جزءاً من التطبيق ولا تُشغَّل مع الاختبارات (ما عدا `SmokeRoutesTest.php` و`TrustedIpApiTest.php`، ونسختاهما في `tests/Feature`). استُخدمت لمقارنة سلوك Laravel 8 بـ Laravel 13 على نفس قاعدة البيانات المحلية (`arabicarabs`). أصلها أدوات nonarab، وأُعيدت كتابة الحسابات والصفحات والنماذج والتصديرات والاستيراد واختبارات الطالب لهذا المشروع.

**قبل أي تشغيل:**
- الـ Exception Handler يرسل كل خطأ إلى Telegram عبر `LogMessageJob` (queue `sync`)، والـ token الحقيقي في جدول `settings` وكقيمة افتراضية داخل `LogMessageJob` نفسه. `tests/TestCase.php` وكل اختبار هنا يعطّله بـ `Bus::fake([LogMessageJob::class])`.
- للأوامر خارج phpunit (السكربتات هنا و`artisan` و`php -S`) شُغّل PHP عبر wrapper يضبط `HTTP_PROXY`/`HTTPS_PROXY`/`ALL_PROXY` (بالحروف الكبيرة والصغيرة) على `http://127.0.0.1:9` فيفشل أي اتصال من Guzzle أو curl (Telegram وFCM وpdfservice)، ويضبط `MAIL_MAILER=log` و`BROADCAST_DRIVER=log` و`TELEGRAM_BOT_TOKEN` بقيمة وهمية. البريد لا يمر عبر الـ proxy، لذلك `MAIL_MAILER=log` ضروري: `SettingServiceProvider` يضع خادم SMTP حقيقياً من جدول `settings`. لـ `config-dump.php` فقط يُشغَّل بـ `UPGRADE_KEEP_ENV=1` (الـ proxy وحده) حتى تظهر إعدادات البريد الحقيقية في المقارنة، وهو لا يرسل شيئاً.
- `FormsSnapshotTest` يتحقق من بريد الطالب بـ `email:rfc,dns`، أي استعلام DNS عن النطاق (لا بيانات): يُستخدم نطاق بريد الطالب نفسه.
- تشغيل واحد فقط في كل مرة على القاعدة، وبدون تعديل كود التطبيق أثناءه.
- الـ helper `t()` يضيف كل مفتاح ترجمة غير موجود إلى ملفات `resources/lang` (ليست في git). `run.sh` يحفظها قبل التشغيل ويعيدها بعده؛ وأي تشغيل آخر لـ phpunit يحتاج نفس الشيء يدوياً. ولأن صفحة "Text translation" تعرض هذه الملفات، تُقارن بين تشغيلين كاملين فقط.

`SmokeRoutesTest` فيه اختيار الحسابات المشترك لكل الاختبارات: المشرف صاحب أكثر صلاحيات، والمدرسة 2139 (أكثر اختبارات لطلاب غير مؤرشفين)، ومعلمها صاحب أكثر طلاب غير مؤرشفين، وطالبه صاحب أكثر اختبارات، والمشرف من المدرسة 740 (المدارس الثلاث التي لمشرفيها معلمون فقط، و2139 ليس فيها مشرفون). كل المشرفين وأغلب المعلمين عليهم تغيير كلمة مرور إجباري في القاعدة المحلية، فـ `unlockAccounts()` يرفعه عن الحسابات الخمسة **داخل transaction الاختبار** فقط. وفيه قائمة routes الـ GET المستثناة (routes الـ artisan العامة، و`seed` و`give_roles` و`copy_*` و`remove_space`، والتي تنتهي بـ `dd()`، والتصديرات والبطاقات والخروج)، و`resetAuth()`.

1. نسخة مرجعية من الكود القديم بجانب المشروع:
   ```
   git -c core.longpaths=true worktree add --detach ../arabic-arabs-l8-ref f0d1f9b3
   ```
   (commit الـ baselines: كود التطبيق فيه مطابق لـ `before-l13`.) ثم نسخ `.env` و`vendor` (من `composer.lock` القديم) و`resources/lang` إليها.
2. تشغيل كل اختبارات المقارنة على نسخة واحدة (تُنسخ مع `SmokeRoutesTest` إلى `tests/Feature` ثم تُشغّل بالترتيب، والنتائج في مجلد الـ snapshots):
   ```
   bash run.sh <wrapper php 7.4> ../arabic-arabs-l8-ref <snapshots القديمة>
   bash run.sh <wrapper php 8.4> . <snapshots الجديدة>
   ```
   ويمكن تمرير أسماء اختبارات بعينها (`Forms ParamPages`). كل ما يُكتب يُلغى (`DatabaseTransactions`)، والوقت مثبّت (`Carbon::setTestNow`). ملفات الاستيراد المرفوعة تُحذف، وملفات Excel المؤقتة في `storage/framework/cache/laravel-excel` يحذفها `run.sh`. بعد الانتهاء تُحذف النسخ من `tests/Feature` (ما عدا `SmokeRoutesTest.php` و`TrustedIpApiTest.php`).
3. المقارنة:
   - `php compare.php <مجلد القديم> <مجلد الجديد> --show=50` لكل الملفات، بعد حذف ما يتغير في كل طلب.
   - `php routes-dump.php <مسار المشروع>` و`php routes-compare.php before.json after.json`، و`php routes-order.php <مسار المشروع>` لترتيب التسجيل (`routes-dump` يرتّب).
   - `php config-dump.php <مسار المشروع>` كل قيم `config()` بعد التشغيل.
   - `php load-all.php <مسار المشروع>` يحمّل كل class في `app/`.

قبل المقارنة بين النسختين يُشغَّل `run.sh` مرتين على النسخة القديمة: الفروق بينهما ids الصفوف الجديدة في activity log وترتيب أسئلة القصة العشوائي. وأي migration جديدة تُطبَّق قبل تشغيل النسخة القديمة المعتمد للمقارنة (الأعمدة الجديدة تظهر في الصفحات التي تعرض صفوفاً كاملة).

| الملف | ما يغطيه |
| --- | --- |
| SmokeRoutesTest | حالة كل صفحة GET بدون parameters لكل guard وللزائر، مقابل `tests/smoke-baseline.json` |
| ResponseSnapshotTest | محتوى نفس الصفحات، كصفحة وكطلب datatables |
| ParamPagesSnapshotTest | صفحات بـ parameters: الدروس (ومنها التي تعرض صوراً وصوتاً وفيديو من medialibrary) والقصص للطالب والمعلم والمشرف، التصحيح والمعاينة والشهادات، الواجبات، الصلاحيات، الدخول كحساب آخر، تغيير اللغة، وطلبات datatables بفلاتر |
| AuthFlowSnapshotTest | الدخول والخروج والصفحة الثانية والقفل وفرض تغيير كلمة المرور والحساب غير المفعّل لكل guard، ودخول المشرف كحساب آخر مرتين، ودخول الطالب (والمؤرشف، و"تذكرني" بالكوكي وحده)، و`login_sessions` |
| PasswordResetSnapshotTest | رسائل استعادة كلمة المرور وصفحة إعادة التعيين والتعيين نفسه للوحات الأربع والطالب |
| LogViewerAccessSnapshotTest | من يستطيع فتح `/log-viewer` |
| ExportSnapshotTest | تصديرات Excel للمشرف ولـ General من اللوحات الأربع، وبطاقات الطلاب، خلية بخلية (`pdfReports` مستثنى: يتصل بخدمة خارجية) |
| ImportSnapshotTest | استيراد طلاب (xlsx وcsv بنص عربي، صفوف صحيحة وخاطئة، بريد مستخدم، back_grade، تحديث) ومعلمين |
| StudentTestsSnapshotTest | الطالب يحل اختبار درس وقصة (صح وخطأ واختيار وتوصيل وترتيب، كاملاً ونصف خاطئ) والتتبع، ثم صفحاته وصفحات المعلم |
| FormsSnapshotTest | نماذج الإضافة والتعديل الرئيسية (فارغة وخاطئة أيضاً)، والصفوف التي كتبتها، وسجل activity log، والبريد |
| TrustedIpApiTest | `api/update_settings` يرفض من يدّعي في ترويسة إعادة توجيه أنه عنوان موثوق |
| `session-check.php` | جلسة تكتبها نسخة وتقرأها الأخرى، بـ `php` و`json` |
| `session-http-check.sh` | دخول حقيقي عبر HTTP (`php -S`) على نسخة ثم متابعة نفس الجلسة على الأخرى، في الاتجاهين، و"تذكرني". يغيّر حسابين مؤقتاً ويعيدهما |
| `cookie-check.php` | كوكي الجلسة المشفر والقيم المشفرة بين النسختين |
| `cache-check.php` | ما يُكتب في الكاش بنسخة ويُقرأ بالأخرى (Setting وCollection ومصفوفة الإعدادات وبيانات الطالب)، وكاش الصلاحيات |
| `mail-render.php` | HTML رسالة استعادة كلمة المرور للمشرف |
| `media-urls.php` | الرابط والمسار ورابط تحويل `minImage` ومساره وحالته لكل صفوف `media` (medialibrary 7 مقابل 11)، وmd5 للقائمة كلها |
