<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): saves the normalized body of the
 * GET pages that take parameters (edit and show pages, previews, reports,
 * certificates, the student and teacher lesson and story pages, logging in as
 * another account, switching the language) and of a few filtered datatables
 * calls, for each guard, into SNAPSHOT_DIR. The ids are picked the same way on
 * both versions, from the accounts of SmokeRoutesTest.
 */
class ParamPagesSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }

    private static function first($table, $where = [], $order = 'id', $desc = false)
    {
        $q = DB::table($table);
        foreach ($where as $k => $v) {
            $q->where($k, $v);
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'deleted_at')) {
            $q->whereNull('deleted_at');
        }

        return $desc ? $q->orderByDesc($order)->value('id') : $q->orderBy($order)->value('id');
    }

    /** Every id the pages below need, picked the same way on both versions. */
    public static function ids()
    {
        $school = SmokeRoutesTest::school();
        $teacher = SmokeRoutesTest::teacher();
        $supervisor = SmokeRoutesTest::supervisor();
        $student = SmokeRoutesTest::student();
        $manager = SmokeRoutesTest::manager();

        $level = DB::table('levels')->where('grade', $student->grade)->whereNull('deleted_at')->orderBy('id')->value('id')
            ?: self::first('levels');
        // the lesson of that level with the most test questions, and with training questions
        $lesson = DB::table('lessons')->where('level_id', $level)->whereNull('lessons.deleted_at')
            ->orderByRaw('(select count(*) from questions where questions.lesson_id = lessons.id and questions.deleted_at is null) desc')
            ->orderBy('id')->value('id');
        $tQuestion = DB::table('t_questions')->where('lesson_id', $lesson)->whereNull('deleted_at')->orderBy('id')->value('id')
            ?: self::first('t_questions');
        $tLesson = DB::table('t_questions')->where('id', $tQuestion)->value('lesson_id');
        $story = DB::table('stories')->where('grade', $student->grade)->whereNull('stories.deleted_at')
            ->orderByRaw('(select count(*) from story_questions where story_questions.story_id = stories.id and story_questions.deleted_at is null) desc')
            ->orderBy('id')->value('id') ?: self::first('stories');
        $storyLevel = DB::table('stories')->where('id', $story)->value('grade');

        $studentIds = DB::table('users')->where('school_id', $school->id)->pluck('id');
        $studentTest = DB::table('student_tests')->where('user_id', $student->id)->whereNull('deleted_at')->orderByDesc('id')->value('id')
            ?: DB::table('student_tests')->whereIn('user_id', $studentIds)->whereNull('deleted_at')->orderByDesc('id')->value('id');
        $storyTest = DB::table('student_story_tests')->where('user_id', $student->id)->whereNull('deleted_at')->orderByDesc('id')->value('id')
            ?: DB::table('student_story_tests')->whereIn('user_id', $studentIds)->whereNull('deleted_at')->orderByDesc('id')->value('id');
        $record = DB::table('user_records')->whereIn('user_id', $studentIds)->whereNull('deleted_at')->orderByDesc('id')->value('id')
            ?: self::first('user_records', [], 'id', true);
        $work = DB::table('user_lessons')->whereIn('user_id', $studentIds)->whereNull('deleted_at')->orderByDesc('id')->value('id')
            ?: self::first('user_lessons', [], 'id', true);
        $lessonAssignment = self::first('lesson_assignments', ['teacher_id' => $teacher->id], 'id', true)
            ?: self::first('lesson_assignments', [], 'id', true);
        $storyAssignment = self::first('story_assignments', ['teacher_id' => $teacher->id], 'id', true)
            ?: self::first('story_assignments', [], 'id', true);
        $certificate = DB::table('teacher_user_certificates')->where('teacher_id', $teacher->id)->orderByDesc('id')->value('id')
            ?: DB::table('teacher_user_certificates')->orderByDesc('id')->value('id');
        $tracked = DB::table('user_tracker_stories')->groupBy('user_id')->orderByRaw('count(*) desc')->orderBy('user_id')->value('user_id');

        return [
            'school' => $school->id, 'teacher' => $teacher->id, 'supervisor' => $supervisor->id,
            'student' => $student->id, 'manager' => $manager->id, 'grade' => $student->grade,
            'level' => $level, 'lesson' => $lesson, 't_question' => $tQuestion, 't_lesson' => $tLesson,
            'story' => $story, 'story_level' => $storyLevel,
            'student_test' => $studentTest, 'story_test' => $storyTest, 'record' => $record, 'work' => $work,
            'lesson_assignment' => $lessonAssignment, 'story_assignment' => $storyAssignment,
            'certificate' => $certificate, 'tracked_student' => $tracked,
            'package' => self::first('packages'), 'year' => self::first('years'),
            'achievement_level' => self::first('achievement_levels'), 'page' => self::first('pages'),
            'page_key' => DB::table('pages')->orderBy('id')->value('key'),
            'role' => self::first('roles'), 'permission' => self::first('permissions'),
            'import_file' => self::first('import_files', [], 'id', true),
            'import_file_logs' => DB::table('import_file_logs')->orderByDesc('id')->value('import_file_id'),
            'contact_us' => self::first('contact_us', [], 'id', true),
            'manager_notification' => DB::table('notifications')->where('notifiable_type', \App\Models\Manager::class)
                ->where('notifiable_id', $manager->id)->orderByDesc('created_at')->orderBy('id')->value('id'),
            'supervisor_notification' => DB::table('notifications')->where('notifiable_type', \App\Models\Supervisor::class)
                ->orderByDesc('created_at')->orderBy('id')->value('id'),
            'student_notification' => DB::table('notifications')->where('notifiable_type', \App\Models\User::class)
                ->where('notifiable_id', $student->id)->orderByDesc('created_at')->orderBy('id')->value('id'),
            'activities' => DB::table('activity_log')->selectRaw('max(id) as id')->groupBy('subject_type')->orderBy('id')->pluck('id')->all(),
        ];
    }

    /** [guard, path] of every page, the ids filled in. */
    public static function targets()
    {
        $i = self::ids();
        $t = [];

        // the student pages (routes/web.php, guard web)
        $s = [
            "lessons/{$i['level']}/level",
            "lessons/{$i['lesson']}/level/learn", "lessons/{$i['lesson']}/level/training", "lessons/{$i['lesson']}/level/test",
            "stories/{$i['story_level']}/level",
            "stories/{$i['story']}/story/watch", "stories/{$i['story']}/story/read", "stories/{$i['story']}/story/test",
            'lessons/certificates', 'stories/certificates', 'motivation/certificates',
            "lessons/certificates-export/{$i['student_test']}", "stories/certificates-export/{$i['story_test']}",
            "motivation/certificates-export/{$i['certificate']}",
            "lessons/certificates-answers/{$i['student_test']}", "stories/certificates-answers/{$i['story_test']}",
            'lang/ar', 'lang/en',
        ];
        if ($i['student_notification']) {
            $s[] = "notification/{$i['student_notification']}/read";
        }
        foreach ($s as $p) {
            $t[] = ['web', $p];
        }
        // public pages
        foreach (["user/{$i['student']}/report", "page/{$i['page_key']}", 'lang/ar', 'password/reset/upgrade-check-token'] as $p) {
            $t[] = ['guest', $p];
        }

        // the General controllers, under each of the four panels
        $general = [
            "supervisor/{$i['supervisor']}/edit", "supervisor/edit-permissions/{$i['supervisor']}", "supervisor/{$i['supervisor']}/login",
            "teacher/{$i['teacher']}/edit", "teacher/edit-permissions/{$i['teacher']}", "teacher/{$i['teacher']}/login",
            "tracking_teachers_report/{$i['teacher']}",
            "user/{$i['student']}/edit", "user/{$i['student']}/card", "user/report/{$i['student']}", "user/{$i['student']}/login",
            "user/lesson_review/{$i['tracked_student']}", "user/story_review/{$i['tracked_student']}",
            "lessons_tests/{$i['student_test']}", "lessons_tests/{$i['student_test']}/edit", "lessons_tests/{$i['student_test']}/preview",
            "lessons_tests/certificate/{$i['student_test']}",
            "stories_tests/{$i['story_test']}", "stories_tests/{$i['story_test']}/edit", "stories_tests/{$i['story_test']}/preview",
            "stories_tests/{$i['story_test']}/certificate",
            "stories_records/{$i['record']}", "stories_records/{$i['record']}/edit",
            "students_works/{$i['work']}", "students_works/{$i['work']}/edit",
            "lesson_assignment/{$i['lesson_assignment']}", "lesson_assignment/{$i['lesson_assignment']}/edit",
            "story_assignment/{$i['story_assignment']}", "story_assignment/{$i['story_assignment']}/edit",
            "motivational_certificates/{$i['certificate']}",
            'pre_usage_report/general', 'pre_usage_report/teacher',
            "role/{$i['role']}", "role/{$i['role']}/edit", "permission/{$i['permission']}", "permission/{$i['permission']}/edit",
            "user_role_and_permission/teacher/{$i['teacher']}/edit", "user_role_and_permission/school/{$i['school']}/edit",
            "getTeacherBySchool/{$i['school']}", "getSectionBySchool/{$i['school']}", "getSectionByTeacher/{$i['teacher']}",
            "getStudentsByGrade/{$i['grade']}",
            "getLevelsByGrade?grade={$i['grade']}", "getLessonsByLevel?level_id={$i['level']}", "getStoriesByGrade?grade={$i['grade']}",
            // list pages called by datatables with the filters they send
            "user?draw=1&start=0&length=10&school_id={$i['school']}&grade={$i['grade']}",
            "user?draw=1&start=0&length=10&teacher_id={$i['teacher']}",
            "lessons_tests?draw=1&start=0&length=10&school_id={$i['school']}",
            "stories_tests?draw=1&start=0&length=10&school_id={$i['school']}",
            "teacher?draw=1&start=0&length=10&school_id={$i['school']}",
            "tracking_teachers?draw=1&start=0&length=10&school_id={$i['school']}",
            "usage_report?school_id={$i['school']}&start_date=2024-09-01&end_date=2025-06-30",
        ];
        foreach (SmokeRoutesTest::PANELS as $panel) {
            foreach ($general as $p) {
                $t[] = [$panel, "$panel/$p"];
            }
            $t[] = [$panel, "$panel/lang/ar"];
            $t[] = [$panel, "$panel/lang/en"];
            $t[] = ['guest', "$panel/password/reset/upgrade-check-token"];
        }

        // manager only
        foreach ([
            "school/{$i['school']}", "school/{$i['school']}/edit", "school/edit-permissions/{$i['school']}", "school-login/{$i['school']}",
            "manager/{$i['manager']}", "manager/{$i['manager']}/edit", "manager/edit-permissions/{$i['manager']}",
            "level/{$i['level']}", "level/{$i['level']}/edit",
            "story/{$i['story']}", "story/{$i['story']}/edit", "story_assessment/{$i['story']}",
            "story/{$i['story']}/review/assessment", "story/{$i['story']}/review/training", "story/{$i['story']}/review/watch",
            "lesson/{$i['lesson']}", "lesson/{$i['lesson']}/edit", "content_lesson/{$i['lesson']}", "assessment_lesson/{$i['lesson']}",
            "lesson_preview/{$i['lesson']}/learn", "lesson_preview/{$i['lesson']}/training", "lesson_preview/{$i['lesson']}/test",
            "training/{$i['t_lesson']}", "training/{$i['t_lesson']}/create?type=1", "training/{$i['t_lesson']}/create?type=3",
            "training/{$i['t_lesson']}/{$i['t_question']}", "training_edit/{$i['t_lesson']}",
            "package/{$i['package']}", "package/{$i['package']}/edit", "year/{$i['year']}", "year/{$i['year']}/edit",
            "achievement_levels/{$i['achievement_level']}", "achievement_levels/{$i['achievement_level']}/edit",
            "roles/{$i['role']}", "roles/{$i['role']}/edit", "page/{$i['page']}/edit",
            "import_files/{$i['import_file']}", "import_files/{$i['import_file']}/edit", "import_files/{$i['import_file_logs']}/show_logs",
            "contact_us/{$i['contact_us']}/show",
            "activity-log?draw=1&start=0&length=10&subject_type=App%5CModels%5CSetting",
            "activity-log?draw=1&start=0&length=10&causer_type=App%5CModels%5CManager",
        ] as $p) {
            $t[] = ['manager', "manager/$p"];
        }
        if ($i['manager_notification']) {
            $t[] = ['manager', "manager/notification/{$i['manager_notification']}"];
        }
        foreach ($i['activities'] as $id) {
            $t[] = ['manager', "manager/activity-log/$id"];
        }
        if ($i['supervisor_notification']) {
            $t[] = ['supervisor', "supervisor/notification/{$i['supervisor_notification']}"];
        }

        // the teacher's curriculum pages
        foreach ([
            "teacher/level/{$i['grade']}", "teacher/level/{$i['grade']}/{$i['level']}",
            "teacher/lesson/{$i['lesson']}/learn/{$i['grade']}", "teacher/lesson/{$i['lesson']}/training/{$i['grade']}",
            "teacher/lesson/{$i['lesson']}/test/{$i['grade']}",
            "teacher/stories_level/{$i['story_level']}", "teacher/stories/{$i['story']}/watch", "teacher/stories/{$i['story']}/read",
        ] as $p) {
            $t[] = ['teacher', $p];
        }

        // Logging in as somebody else and switching the language write to the
        // accounts (last_login, local, login_sessions): open them last, so the
        // other pages do not show rows written at the time of the run.
        $late = function ($target) {
            return (bool) preg_match('#(/login|school-login/\d+|/lang/\w+|^lang/\w+|/read)$#', $target[1]);
        };

        return array_merge(array_values(array_filter($t, function ($x) use ($late) {
            return !$late($x);
        })), array_values(array_filter($t, $late)));
    }

    public function test_snapshots()
    {
        // the same "now" on both versions and on both sides of midnight
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');
        $dir = getenv('SNAPSHOT_DIR');
        $this->assertNotEmpty($dir);
        @mkdir($dir, 0777, true);

        $users = [
            'guest' => null,
            'manager' => SmokeRoutesTest::manager(),
            'school' => SmokeRoutesTest::school(),
            'teacher' => SmokeRoutesTest::teacher(),
            'supervisor' => SmokeRoutesTest::supervisor(),
            'web' => SmokeRoutesTest::student(),
        ];
        $targets = self::targets();
        file_put_contents("$dir/_param_targets.json", json_encode($targets, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $seen = [];
        foreach ($targets as [$guard, $path]) {
            $ajax = strpos($path, 'draw=1') !== false || preg_match('#/get[A-Z]#', $path);
            if ($users[$guard]) {
                $this->actingAs($users[$guard], $guard);
            }
            $headers = $ajax ? ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json, text/javascript, */*; q=0.01'] : [];
            $response = $this->get('/' . $path, $headers);
            $name = $guard . '__' . trim(preg_replace('/[^A-Za-z0-9_-]+/', '_', $path), '_');
            $seen[$name] = ($seen[$name] ?? 0) + 1;
            if ($seen[$name] > 1) {
                $name .= '__' . $seen[$name];
            }
            file_put_contents("$dir/$name.txt", ResponseSnapshotTest::describe($response));
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        $this->assertNotEmpty($targets);
    }
}
