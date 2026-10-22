<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): submits the main add and edit
 * forms of every panel and of the student (valid, empty and wrong), and writes
 * to SNAPSHOT_DIR what each one answered (status, redirect, flash, validation
 * errors), the rows it wrote, the activity log and notification rows it
 * added, and the mails it sent. Everything is rolled back. No form here uploads
 * a file, so nothing is written to public/uploads.
 */
class FormsSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    /** tables whose new rows are recorded after every form */
    private const WATCH = ['schools', 'managers', 'levels', 'years', 'packages', 'teachers', 'supervisors', 'users',
        'supervisor_teachers', 'teacher_students', 'lesson_assignments', 'story_assignments', 'user_assignments',
        'user_story_assignments', 'teacher_user_certificates', 'password_histories', 'model_has_permissions', 'model_has_roles'];

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }

    public function test_forms()
    {
        Mail::fake();
        Notification::fake();
        $dir = getenv('SNAPSHOT_DIR');
        @mkdir($dir, 0777, true);
        // the dates written by the forms (deadlines, password ages) do not depend on the day of the run
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');

        $i = ParamPagesSnapshotTest::ids();
        $users = [
            'manager' => SmokeRoutesTest::manager(),
            'school' => SmokeRoutesTest::school(),
            'teacher' => SmokeRoutesTest::teacher(),
            'supervisor' => SmokeRoutesTest::supervisor(),
            'web' => SmokeRoutesTest::student(),
        ];
        $password = 'Upgrade-Check-2026!';
        foreach (['managers' => 'manager', 'schools' => 'school', 'teachers' => 'teacher', 'supervisors' => 'supervisor', 'users' => 'web'] as $table => $guard) {
            DB::table($table)->where('id', $users[$guard]->id)->update(['password' => \Illuminate\Support\Facades\Hash::make($password)]);
            $users[$guard]->refresh();
        }
        $year = $i['year'];
        $students = DB::table('teacher_students')->where('teacher_id', $i['teacher'])->whereNull('deleted_at')->orderBy('user_id')->limit(3)->pluck('user_id')->all();
        $lessons = DB::table('lessons')->where('level_id', $i['level'])->whereNull('deleted_at')->orderBy('id')->limit(2)->pluck('id')->all();
        $stories = DB::table('stories')->where('grade', $i['story_level'])->whereNull('deleted_at')->orderBy('id')->limit(2)->pluck('id')->all();
        $student = $users['web'];
        $new = 'Upgrade-New-2026!';

        $cases = [
            // manager panel
            ['manager', 'post', '/manager/school', [], 'schools', null],
            ['manager', 'post', '/manager/school', ['name' => ['ar' => 'مدرسة الفحص', 'en' => 'Upgrade school'], 'email' => 'upgrade-school@example.invalid', 'password' => '123456', 'mobile' => '0501234567', 'website' => 'https://example.invalid'], 'schools', null],
            ['manager', 'put', "/manager/school/{$i['school']}", ['name' => ['ar' => 'مدرسة معدلة', 'en' => 'Edited school'], 'email' => $users['school']->email, 'mobile' => '0509999999', 'website' => 'https://example.invalid/edit'], 'schools', $i['school']],
            ['manager', 'post', '/manager/manager', ['name' => 'Upgrade manager', 'email' => 'not-an-email', 'password' => '1', 'mail_notification' => 3], 'managers', null],
            ['manager', 'post', '/manager/manager', ['name' => 'Upgrade manager', 'email' => 'upgrade-manager@example.invalid', 'password' => '123456', 'mail_notification' => 1], 'managers', null],
            ['manager', 'post', '/manager/level', ['name' => ['ar' => 'مستوى', 'en' => 'Level'], 'grade' => 'x', 'level_mark' => 120], 'levels', null],
            ['manager', 'post', '/manager/level', ['name' => ['ar' => 'مستوى', 'en' => 'Level'], 'grade' => 3, 'level_mark' => 50, 'level_note' => 'note', 'color' => '#ff0000', 'text_color' => '#ffffff'], 'levels', null],
            ['manager', 'put', "/manager/level/{$i['level']}", ['name' => ['ar' => 'مستوى معدل', 'en' => 'Edited level'], 'grade' => $i['grade'], 'level_mark' => 60, 'level_note' => 'edited'], 'levels', $i['level']],
            ['manager', 'post', '/manager/year', ['name' => ['ar' => 'سنة', 'en' => 'Year'], 'slug' => '2099/2100'], 'years', null],
            ['manager', 'put', "/manager/year/$year", ['name' => ['ar' => 'سنة معدلة', 'en' => 'Edited year'], 'slug' => 'edited'], 'years', $year],
            ['manager', 'post', '/manager/package', ['name' => ['ar' => 'باقة', 'en' => 'Package'], 'days' => 30, 'price' => 10], 'packages', null],
            ['manager', 'put', "/manager/package/{$i['package']}", ['name' => ['ar' => 'باقة معدلة', 'en' => 'Edited package'], 'days' => 31, 'price' => 11], 'packages', $i['package']],
            ['manager', 'put', "/manager/achievement_levels/{$i['achievement_level']}", ['name' => ['ar' => 'مستوى إنجاز', 'en' => 'Achievement'], 'description' => ['ar' => 'وصف', 'en' => 'Description'], 'required_points' => 15], 'achievement_levels', $i['achievement_level']],
            ['manager', 'post', '/manager/profile', ['name' => 'Edited manager', 'email' => $users['manager']->email], 'managers', $users['manager']->id],
            ['manager', 'post', '/manager/password', ['old_password' => 'wrong', 'password' => 'short', 'password_confirmation' => 'other'], 'managers', $users['manager']->id],
            ['manager', 'post', '/manager/password', ['old_password' => $password, 'password' => $new, 'password_confirmation' => $new], 'managers', $users['manager']->id],
        ];

        // the General forms, from the manager and from the school
        foreach (['manager', 'school'] as $panel) {
            $tag = $panel === 'manager' ? 'm' : 's';
            $cases[] = [$panel, 'post', "/$panel/teacher", [], 'teachers', null];
            $cases[] = [$panel, 'post', "/$panel/teacher", ['name' => "Upgrade teacher $tag", 'email' => "upgrade-teacher-$tag@example.invalid", 'password' => '123456', 'mobile' => '0501111111', 'school_id' => $i['school'], 'active_to' => '2027-06-30'], 'teachers', null];
            $cases[] = [$panel, 'put', "/$panel/teacher/{$i['teacher']}", ['name' => "Edited teacher $tag", 'email' => $users['teacher']->email, 'school_id' => $i['school'], 'active_to' => '2027-07-31'], 'teachers', $i['teacher']];
            $cases[] = [$panel, 'post', "/$panel/supervisor", ['name' => "Upgrade supervisor $tag", 'email' => "upgrade-supervisor-$tag@example.invalid", 'password' => '123456', 'school_id' => $i['school'], 'teachers' => [$i['teacher']]], 'supervisors', null];
            $cases[] = [$panel, 'put', "/$panel/supervisor/{$i['supervisor']}", ['name' => "Edited supervisor $tag", 'email' => $users['supervisor']->email, 'school_id' => $i['school'], 'teachers' => [$i['teacher']]], 'supervisors', $i['supervisor']];
            $cases[] = [$panel, 'post', "/$panel/user", ['name' => 'x'], 'users', null];
            $cases[] = [$panel, 'post', "/$panel/user", [
                'name' => 'Upgrade Check Student', 'email' => "upgrade-student-$tag@example.invalid", 'password' => '123456',
                'school_id' => $i['school'], 'teacher_id' => $i['teacher'], 'grade' => $i['grade'], 'year_learning' => 1,
                'year_id' => $year, 'package_id' => $i['package'], 'active_to' => '2027-06-30', 'gender' => 'Boy',
                'id_number' => "UPG-$tag-1", 'direct_email' => "parent-$tag@example.invalid", 'section' => 'A', 'mobile' => '0502222222',
            ], 'users', null];
            $cases[] = [$panel, 'put', "/$panel/user/{$student->id}", [
                'name' => 'Edited Check Student', 'email' => $student->email, 'school_id' => $i['school'], 'teacher_id' => $i['teacher'],
                'grade' => $student->grade, 'year_learning' => 2, 'year_id' => $year, 'package_id' => $student->package_id ?: $i['package'],
                'active_to' => '2027-06-30', 'gender' => 'Girl', 'id_number' => "UPG-$tag-2", 'direct_email' => "parent2-$tag@example.invalid",
            ], 'users', $student->id];
            $cases[] = [$panel, 'post', "/$panel/user/activation", ['row_id' => [$student->id], 'active' => 0], 'users', $student->id];
        }

        // the teacher's assignments and certificates
        $cases[] = ['teacher', 'post', '/teacher/lesson_assignment', [], 'lesson_assignments', null];
        $cases[] = ['teacher', 'post', '/teacher/lesson_assignment', [
            'school_id' => $i['school'], 'teacher_id' => $i['teacher'], 'year_id' => $year, 'level_id' => $i['level'],
            'lessons_ids' => $lessons, 'learning_years' => [1, 2, 3, 4, 5], 'students' => $students, 'grade' => $i['grade'],
            'deadline' => '2026-12-31', 'exclude_students' => 2, 'tasks_assignment' => 1, 'test_assignment' => 1,
        ], 'lesson_assignments', null];
        $cases[] = ['teacher', 'post', '/teacher/story_assignment', [
            'school_id' => $i['school'], 'teacher_id' => $i['teacher'], 'year_id' => $year, 'students_grade' => $i['grade'],
            'story_grade' => $i['story_level'], 'stories_ids' => $stories, 'learning_years' => [1, 2, 3, 4, 5], 'students' => $students,
            'deadline' => '2026-12-31', 'exclude_students' => 2,
        ], 'story_assignments', null];
        $cases[] = ['teacher', 'post', '/teacher/motivational_certificates', [
            'teacher_id' => $i['teacher'], 'students' => $students, 'model_type' => 'lesson', 'model_id' => $lessons[0] ?? null,
            'lesson_id' => $lessons[0] ?? null, 'granted_in' => '2026-10-22',
        ], 'teacher_user_certificates', null];

        // profiles and passwords of the other panels and of the student
        $cases[] = ['school', 'post', '/school/profile', ['name' => 'Edited school name', 'email' => $users['school']->email, 'mobile' => '0503333333', 'website' => 'https://example.invalid'], 'schools', $i['school']];
        $cases[] = ['school', 'post', '/school/password', ['old_password' => $password, 'password' => $new, 'password_confirmation' => $new], 'schools', $i['school']];
        $cases[] = ['teacher', 'post', '/teacher/profile', ['name' => 'Edited teacher name', 'email' => $users['teacher']->email, 'mobile' => '0504444444'], 'teachers', $i['teacher']];
        $cases[] = ['teacher', 'post', '/teacher/password', ['old_password' => 'wrong', 'password' => $new, 'password_confirmation' => $new], 'teachers', $i['teacher']];
        $cases[] = ['supervisor', 'post', '/supervisor/profile', ['name' => 'Edited supervisor name', 'email' => $users['supervisor']->email], 'supervisors', $i['supervisor']];
        $cases[] = ['supervisor', 'post', '/supervisor/password', ['old_password' => $password, 'password' => $new, 'password_confirmation' => $new], 'supervisors', $i['supervisor']];
        $cases[] = ['web', 'post', '/profile/update', ['name' => $student->name, 'email' => $student->email, 'country_code' => '971', 'short_country' => 'AE', 'mobile' => '050 123 4567', 'year_learning' => 2], 'users', $student->id];
        $cases[] = ['web', 'post', '/profile/update', ['name' => $student->name, 'email' => $student->email, 'country_code' => '971', 'short_country' => 'AE', 'mobile' => 'not a number', 'year_learning' => 2], 'users', $student->id];
        $cases[] = ['web', 'post', '/password/update', ['old_password' => $password, 'password' => $new, 'password_confirmation' => $new], 'users', $student->id];

        $n = 0;
        // rows written by the forms so far (a later form may point at them)
        $new = [];
        foreach ($cases as [$guard, $method, $url, $data, $table, $id]) {
            $n++;
            $this->actingAs($users[$guard], $guard);
            $max = [];
            foreach (array_merge(self::WATCH, ['activity_log', 'login_sessions']) as $t) {
                $max[$t] = \Illuminate\Support\Facades\Schema::hasColumn($t, 'id') ? (int) DB::table($t)->max('id') : 0;
            }
            $notifications = DB::table('notifications')->count();

            $response = $this->call(strtoupper($method), $url, $data, [], [], ['HTTP_REFERER' => url('/upgrade-check-referer')]);
            $out = "FORM $guard $method $url\nDATA " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n" . ResponseSnapshotTest::describe($response);
            if (strlen($out) > 3000) {
                $out = substr($out, 0, 3000) . "\n[...]";
            }
            $out .= "\n\nROW " . ($id ? json_encode($this->row($table, $id), JSON_UNESCAPED_UNICODE) : '-') . "\n";
            // The ids of the rows written here differ between runs: a column that
            // points at a row of this form (by its table) is printed as NEW_ID.
            $rows = [];
            foreach (self::WATCH as $t) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn($t, 'id')) {
                    continue;
                }
                foreach (DB::table($t)->where('id', '>', $max[$t])->orderBy('id')->get() as $row) {
                    $new[$t][] = $row->id;
                    $rows[] = [$t, (array) $row];
                }
            }
            $isNew = function ($table, $value) use (&$new) {
                return $value !== null && in_array((int) $value, $new[$table] ?? [], true);
            };
            $fks = ['user_id' => 'users', 'teacher_id' => 'teachers', 'supervisor_id' => 'supervisors', 'school_id' => 'schools',
                'level_id' => 'levels', 'package_id' => 'packages', 'year_id' => 'years', 'manager_id' => 'managers'];
            $mark = function (array $row) use ($isNew, $fks) {
                foreach ($fks as $column => $table) {
                    if (array_key_exists($column, $row) && $isNew($table, $row[$column])) {
                        $row[$column] = 'NEW_ID';
                    }
                }
                foreach (['subject' => 'subject_type', 'model' => 'model_type', 'causer' => 'causer_type'] as $prefix => $type) {
                    if (!empty($row[$type]) && class_exists($row[$type]) && $isNew((new $row[$type]())->getTable(), $row[$prefix . '_id'] ?? null)) {
                        $row[$prefix . '_id'] = 'NEW_ID';
                    }
                }

                return $row;
            };
            foreach ($rows as [$t, $row]) {
                $out .= "NEW $t " . json_encode($this->clean($mark($row)), JSON_UNESCAPED_UNICODE) . "\n";
            }
            foreach (DB::table('activity_log')->where('id', '>', $max['activity_log'])->orderBy('id')->get() as $row) {
                $row = (array) $row;
                unset($row['id'], $row['batch_uuid'], $row['event']);
                $row['properties'] = json_decode($row['properties'], true);
                $out .= "ACTIVITY " . json_encode($this->clean($mark($row)), JSON_UNESCAPED_UNICODE) . "\n";
            }
            // a redirect to the edit page of the new row
            foreach ($new[$table] ?? [] as $newId) {
                $out = preg_replace('#^(LOCATION .*/)' . $newId . '(/|$)#m', '${1}NEW_ID$2', $out);
            }
            $out .= 'NOTIFICATION ROWS ' . (DB::table('notifications')->count() - $notifications) . "\n";
            file_put_contents(sprintf('%s/form_%02d_%s.txt', $dir, $n, trim(preg_replace('/[^A-Za-z0-9]+/', '_', "$guard $method $url"), '_')), $out);
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        file_put_contents("$dir/form_mails.txt", 'MAILS ' . json_encode($this->mails(), JSON_UNESCAPED_UNICODE) . "\n");
        $this->assertTrue(true);
    }

    private function mails()
    {
        $out = [];
        $fake = Mail::getFacadeRoot();
        foreach (['mailables', 'queuedMailables'] as $property) {
            $p = new \ReflectionProperty($fake, $property);
            $p->setAccessible(true); // PHP 7.4
            foreach ($p->getValue($fake) as $mailable) {
                $out[] = $property . ':' . get_class($mailable);
            }
        }
        sort($out);

        return $out;
    }

    private function row($table, $id)
    {
        $row = DB::table($table)->where('id', $id)->first();

        return $row ? $this->clean((array) $row) : null;
    }

    private function clean(array $row)
    {
        foreach ($row as $k => $v) {
            if (in_array($k, ['id', 'created_at', 'updated_at', 'remember_token'], true)) {
                unset($row[$k]);
            } elseif (is_string($v) && preg_match('/^\$2y\$/', $v)) {
                $row[$k] = 'HASH';
            }
        }
        if (isset($row['properties']) && is_array($row['properties'])) {
            array_walk_recursive($row['properties'], function (&$v, $k) {
                if (is_string($v) && preg_match('/^\$2y\$/', $v)) {
                    $v = 'HASH';
                }
            });
            foreach (['attributes', 'old'] as $part) {
                unset($row['properties'][$part]['id']);
            }
        }

        return $row;
    }
}
