<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): saves the normalized body of the
 * GET pages that take parameters (edit and show pages, previews, reports,
 * certificates, the student and teacher lesson and story pages, the lessons
 * whose images, audio and video come from medialibrary, logging in as
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
        SmokeRoutesTest::unlockAccounts();
    }

    private static function first($table, $where = [], $order = 'id', $desc = false)
    {
        $q = DB::table($table);
        foreach ($where as $k => $v) {
            is_array($v) ? $q->whereIn($k, $v) : $q->where($k, $v);
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'deleted_at')) {
            $q->whereNull('deleted_at');
        }

        return $desc ? $q->orderByDesc($order)->value('id') : $q->orderBy($order)->value('id');
    }

    /** The first lesson (not deleted) that owns a file of this medialibrary collection. */
    private static function lessonWithMedia($modelType, $collection, $lessonOf)
    {
        $modelId = DB::table('media')->where('model_type', $modelType)->where('collection_name', $collection)
            ->orderBy('id')->pluck('model_id');
        foreach ($modelId as $id) {
            $lesson = $lessonOf($id);
            if ($lesson && DB::table('lessons')->where('id', $lesson)->whereNull('deleted_at')->exists()) {
                return $lesson;
            }
        }

        return null;
    }

    /** Every id the pages below need, picked the same way on both versions. */
    public static function ids()
    {
        $school = SmokeRoutesTest::school();
        $teacher = SmokeRoutesTest::teacher();
        $supervisor = SmokeRoutesTest::supervisor();
        $student = SmokeRoutesTest::student();
        $manager = SmokeRoutesTest::manager();
        $grade = $student->grade_id;

        // the lesson of the student's grade with the most test questions
        $lesson = DB::table('lessons')->where('grade_id', $grade)->whereNull('lessons.deleted_at')
            ->orderByRaw('(select count(*) from questions where questions.lesson_id = lessons.id and questions.deleted_at is null) desc')
            ->orderBy('id')->value('id');
        $lessonRow = DB::table('lessons')->where('id', $lesson)->first();
        $tLesson = DB::table('t_questions')->whereNull('deleted_at')->whereIn('lesson_id', DB::table('lessons')->where('grade_id', $grade)->whereNull('deleted_at')->pluck('id'))
            ->orderBy('lesson_id')->value('lesson_id') ?: DB::table('t_questions')->whereNull('deleted_at')->orderBy('lesson_id')->value('lesson_id');
        $tQuestion = self::first('t_questions', ['lesson_id' => $tLesson]);
        $question = self::first('questions', ['lesson_id' => $lesson]);

        // lessons whose files come from each medialibrary collection
        $byQuestion = function ($id) { return DB::table('questions')->where('id', $id)->value('lesson_id'); };
        $byTQuestion = function ($id) { return DB::table('t_questions')->where('id', $id)->value('lesson_id'); };
        $media = array_filter([
            'image' => self::lessonWithMedia(\App\Models\Lesson::class, 'imageLessons', function ($id) { return $id; }),
            'audio' => self::lessonWithMedia(\App\Models\Lesson::class, 'audioLessons', function ($id) { return $id; }),
            'video' => self::lessonWithMedia(\App\Models\Lesson::class, 'videoLessons', function ($id) { return $id; }),
            'question' => self::lessonWithMedia(\App\Models\Question::class, 'imageQuestion', $byQuestion),
            't_question' => self::lessonWithMedia(\App\Models\TQuestion::class, 't_imageQuestion', $byTQuestion),
            'match' => self::lessonWithMedia(\App\Models\QMatch::class, 'match', function ($id) use ($byQuestion) {
                return $byQuestion(DB::table('matches')->where('id', $id)->value('question_id'));
            }),
            't_match' => self::lessonWithMedia(\App\Models\TMatch::class, 't_match', function ($id) use ($byTQuestion) {
                return $byTQuestion(DB::table('t_matches')->where('id', $id)->value('t_question_id'));
            }),
        ]);

        $story = DB::table('stories')->where('grade', $grade)->whereNull('stories.deleted_at')
            ->orderByRaw('(select count(*) from story_questions where story_questions.story_id = stories.id and story_questions.deleted_at is null) desc')
            ->orderBy('id')->value('id') ?: self::first('stories');

        $studentIds = DB::table('users')->where('school_id', $school->id)->pluck('id')->all();
        $userTest = self::first('user_tests', ['user_id' => $student->id], 'id', true)
            ?: self::first('user_tests', ['user_id' => $studentIds], 'id', true);
        $storyTest = self::first('student_story_tests', ['user_id' => $student->id], 'id', true)
            ?: self::first('student_story_tests', ['user_id' => $studentIds], 'id', true);
        $record = self::first('story_user_records', ['user_id' => $studentIds], 'id', true)
            ?: self::first('story_user_records', [], 'id', true);
        $lessonAssignment = self::first('lesson_assignments', ['teacher_id' => $teacher->id], 'id', true)
            ?: self::first('lesson_assignments', [], 'id', true);
        $storyAssignment = self::first('story_assignments', ['teacher_id' => $teacher->id], 'id', true)
            ?: self::first('story_assignments', [], 'id', true);
        $certificate = self::first('motivational_certificates', ['teacher_id' => $teacher->id], 'id', true)
            ?: self::first('motivational_certificates', [], 'id', true);
        $studentCertificate = self::first('motivational_certificates', ['user_id' => $student->id], 'id', true) ?: $certificate;
        $tracked = DB::table('user_trackers')->whereIn('user_id', $studentIds)->groupBy('user_id')->orderByRaw('count(*) desc')->orderBy('user_id')->value('user_id')
            ?: $student->id;
        $trackedStory = DB::table('user_tracker_stories')->whereIn('user_id', $studentIds)->groupBy('user_id')->orderByRaw('count(*) desc')->orderBy('user_id')->value('user_id')
            ?: $student->id;

        return [
            'school' => $school->id, 'teacher' => $teacher->id, 'supervisor' => $supervisor->id,
            'student' => $student->id, 'manager' => $manager->id, 'grade' => $grade,
            'lesson' => $lesson, 'lesson_type' => $lessonRow->lesson_type, 'lesson_level' => $lessonRow->level ?: 1,
            'question' => $question, 't_lesson' => $tLesson, 't_question' => $tQuestion, 'media_lessons' => $media,
            'story' => $story,
            'user_test' => $userTest, 'story_test' => $storyTest, 'record' => $record,
            'lesson_assignment' => $lessonAssignment, 'story_assignment' => $storyAssignment,
            'certificate' => $certificate, 'student_certificate' => $studentCertificate,
            'tracked_student' => $tracked, 'tracked_story_student' => $trackedStory,
            'package' => self::first('packages'), 'year' => self::first('years'),
            'achievement_level' => self::first('achievement_levels'),
            'page_key' => DB::table('pages')->whereNull('deleted_at')->orderBy('id')->value('key'),
            'role' => self::first('roles'), 'permission' => self::first('permissions'),
            'import_file' => self::first('import_files', [], 'id', true),
            'import_file_logs' => DB::table('import_file_logs')->orderByDesc('id')->value('import_file_id'),
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
            "lessons/{$i['grade']}/sub-levels/{$i['lesson_type']}",
            "lessons/{$i['grade']}/level/{$i['lesson_type']}", "lessons/{$i['grade']}/level/{$i['lesson_type']}/{$i['lesson_level']}",
            "lesson/{$i['lesson']}/level/learn", "lesson/{$i['lesson']}/level/training", "lesson/{$i['lesson']}/level/test",
            "stories/{$i['grade']}/level",
            "stories/{$i['story']}/story/watch", "stories/{$i['story']}/story/read", "stories/{$i['story']}/story/test",
            'lessons/certificates', 'stories/certificates', 'motivation/certificates',
            "lessons/certificates-export/{$i['user_test']}", "stories/certificates-export/{$i['story_test']}",
            "motivation/certificates-export/{$i['student_certificate']}",
            "lessons/certificates-answers/{$i['user_test']}", "stories/certificates-answers/{$i['story_test']}",
            'lang/ar', 'lang/en',
        ];
        foreach ($i['media_lessons'] as $mediaLesson) {
            foreach (['learn', 'training', 'test'] as $key) {
                $s[] = "lesson/$mediaLesson/level/$key";
            }
        }
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
            "supervisor/{$i['supervisor']}/edit", "supervisor/{$i['supervisor']}/login",
            "teacher/{$i['teacher']}/edit", "teacher/{$i['teacher']}/login",
            "tracking_teachers_report/{$i['teacher']}",
            "user/{$i['student']}/edit", "user/{$i['student']}/card", "user/report/{$i['student']}", "user/{$i['student']}/login",
            "user/lesson_review/{$i['tracked_student']}", "user/story_review/{$i['tracked_story_student']}",
            "lessons_tests/{$i['user_test']}/edit", "lessons_tests/preview_answers/{$i['user_test']}",
            "lessons_tests/correcting/{$i['user_test']}", "lessons_tests/correcting_feedback/{$i['user_test']}",
            "lessons_tests/certificate/{$i['user_test']}",
            "stories_tests/{$i['story_test']}/edit", "stories_tests/correcting/{$i['story_test']}",
            "stories_tests/{$i['story_test']}/certificate",
            "stories_records/{$i['record']}", "stories_records/{$i['record']}/edit",
            "lesson_assignment/{$i['lesson_assignment']}", "lesson_assignment/{$i['lesson_assignment']}/edit",
            "story_assignment/{$i['story_assignment']}", "story_assignment/{$i['story_assignment']}/edit",
            "motivational_certificates/{$i['certificate']}",
            "role/{$i['role']}", "role/{$i['role']}/edit", "permission/{$i['permission']}", "permission/{$i['permission']}/edit",
            "user_role_and_permission/teacher/{$i['teacher']}/edit", "user_role_and_permission/supervisor/{$i['supervisor']}/edit",
            "getTeacherBySchool/{$i['school']}", "getSectionBySchool/{$i['school']}", "getSectionByTeacher/{$i['teacher']}",
            "getStudentsByGrade/{$i['grade']}",
            "getLessonsByGrade?grade_id={$i['grade']}", "getStoriesByGrade?grade={$i['grade']}",
            // list pages called by datatables with the filters they send
            "user?draw=1&start=0&length=10&school_id={$i['school']}&grade_id={$i['grade']}",
            "user?draw=1&start=0&length=10&teacher_id={$i['teacher']}",
            "lessons_tests?draw=1&start=0&length=10&school_id={$i['school']}",
            "stories_tests?draw=1&start=0&length=10&school_id={$i['school']}",
            "stories_records?draw=1&start=0&length=10&school_id={$i['school']}",
            "teacher?draw=1&start=0&length=10&school_id={$i['school']}",
            "tracking_teachers?draw=1&start=0&length=10&school_id={$i['school']}",
            "user_lesson_assignment?draw=1&start=0&length=10&school_id={$i['school']}",
            "user_story_assignment?draw=1&start=0&length=10&school_id={$i['school']}",
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
        $manager = [
            "school/{$i['school']}/edit", "school/{$i['school']}/login",
            "manager/{$i['manager']}/edit", "user_role_and_permission/school/{$i['school']}/edit",
            "user_role_and_permission/manager/{$i['manager']}/edit",
            "story/{$i['story']}/edit", "story/{$i['story']}/assessment",
            "story/{$i['story']}/review/assessment", "story/{$i['story']}/review/training", "story/{$i['story']}/review/watch",
            "lesson/{$i['t_lesson']}/training", "lesson/{$i['t_lesson']}/training/edit/{$i['t_question']}",
            "package/{$i['package']}/edit", "year/{$i['year']}/edit",
            "achievement_levels/{$i['achievement_level']}/edit",
            "import_files/{$i['import_file']}/edit", "import_files/{$i['import_file_logs']}/show_logs",
            "activity-log?draw=1&start=0&length=10&subject_type=App%5CModels%5CSetting",
            "activity-log?draw=1&start=0&length=10&causer_type=App%5CModels%5CManager",
            "lesson?draw=1&start=0&length=10&grade_id={$i['grade']}",
            'wrong_audio_lessons',
        ];
        // the lesson pages of the manager, for the lesson with the most questions
        // and for every lesson that shows a medialibrary file
        foreach (array_unique(array_merge([$i['lesson']], array_values($i['media_lessons']))) as $l) {
            foreach (["lesson/$l/edit", "lesson/$l/learn", "lesson/$l/assessment", "lesson/$l/training",
                "lesson/$l/review/learn", "lesson/$l/review/training", "lesson/$l/review/test"] as $p) {
                $manager[] = $p;
            }
        }
        if ($i['question']) {
            $manager[] = "lesson/{$i['lesson']}/assessment/edit/{$i['question']}";
        }
        foreach ($manager as $p) {
            $t[] = ['manager', "manager/$p"];
        }
        foreach ($i['activities'] as $id) {
            $t[] = ['manager', "manager/activity-log/$id"];
        }

        // the teacher's curriculum pages
        foreach ([
            "teacher/curriculum/{$i['grade']}", "teacher/levels/{$i['grade']}",
            "teacher/lessons_levels/{$i['grade']}/{$i['lesson_type']}", "teacher/lessons/{$i['grade']}/{$i['lesson_type']}",
            "teacher/sub_lessons/{$i['grade']}/{$i['lesson_type']}/{$i['lesson_level']}",
            "teacher/lesson/{$i['lesson']}/learn", "teacher/lesson/{$i['lesson']}/training", "teacher/lesson/{$i['lesson']}/test",
            "teacher/stories/{$i['grade']}", "teacher/stories_level/{$i['grade']}",
            "teacher/stories/{$i['story']}/watch/{$i['grade']}", "teacher/stories/{$i['story']}/read/{$i['grade']}",
            "teacher/stories/{$i['story']}/test/{$i['grade']}",
        ] as $p) {
            $t[] = ['teacher', $p];
        }

        // Logging in as somebody else and switching the language write to the
        // accounts (last_login, lang, login_sessions): open them last, so the
        // other pages do not show rows written at the time of the run.
        $late = function ($target) {
            return (bool) preg_match('#(/login|/lang/\w+|^lang/\w+|/read)$#', $target[1]);
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
        file_put_contents("$dir/_param_ids.json", json_encode(self::ids(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

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
