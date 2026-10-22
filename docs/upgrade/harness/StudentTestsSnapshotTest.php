<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): the student of SmokeRoutesTest
 * answers a lesson test and a story test (true/false, choice, matching and
 * sorting; once with every answer right, once half wrong), tracks a lesson
 * and a story, and the teacher opens the results.
 * Writes the answers, the rows each step added (results, totals, pass/fail,
 * trackers, points, assignments) and the pages to SNAPSHOT_DIR. Everything is
 * rolled back.
 */
class StudentTestsSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    private const WATCH = ['user_tests', 'true_false_results', 'option_results', 'match_results', 'sort_results',
        'student_story_tests', 'story_true_false_results', 'story_option_results', 'story_match_results', 'story_sort_results',
        'user_trackers', 'user_tracker_stories', 'user_lessons', 'story_user_records', 'user_grades', 'user_achievement_levels',
        'user_streak_rewards', 'notifications'];

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
        SmokeRoutesTest::unlockAccounts();
    }

    /** answers for a lesson (prefix '') or story (prefix 'story_') test, $right of them correct */
    private function answers($questions, $prefix, $right)
    {
        $fk = $prefix === '' ? 'question_id' : 'story_question_id';
        $t = $prefix === '' ? ['true_falses', 'options', 'matches', 'sort_words'] : ['story_true_falses', 'story_options', 'story_matches', 'story_sort_words'];
        $data = ['start_at' => '2026-10-22 09:50:00'];
        foreach ($questions as $n => $q) {
            $ok = $right || $n % 2 === 0;
            if ($q->type == 1) {
                $result = (int) DB::table($t[0])->where($fk, $q->id)->value('result');
                $data['tf'][$q->id] = $ok ? $result : 1 - $result;
            } elseif ($q->type == 2) {
                $options = DB::table($t[1])->where($fk, $q->id)->whereNull('deleted_at')->orderBy('id')->get();
                $correct = $options->firstWhere('result', 1) ?: $options->first();
                $wrong = $options->first(function ($o) use ($correct) { return $o->id !== optional($correct)->id; }) ?: $correct;
                if ($correct) {
                    $data['option'][$q->id] = $ok ? $correct->id : $wrong->id;
                }
            } elseif ($q->type == 3) {
                $matches = DB::table($t[2])->where($fk, $q->id)->whereNull('deleted_at')->orderBy('id')->get();
                $ids = $matches->pluck('id')->all();
                foreach ($matches as $k => $m) {
                    $data['matching'][$q->id][$m->uid] = $ok ? $m->id : $ids[($k + 1) % count($ids)];
                }
            } elseif ($q->type == 4) {
                $words = DB::table($t[3])->where($fk, $q->id)->whereNull('deleted_at')->orderBy('id')->get();
                $list = $ok ? $words : $words->reverse();
                foreach ($list as $w) {
                    $data['sorting'][$q->id][$w->uid] = $w->id;
                }
            }
        }

        return $data;
    }

    public function test_student_tests()
    {
        $dir = getenv('SNAPSHOT_DIR');
        @mkdir($dir, 0777, true);
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');
        $i = ParamPagesSnapshotTest::ids();
        $student = SmokeRoutesTest::student();
        $teacher = SmokeRoutesTest::teacher();

        $lessonQuestions = DB::table('questions')->where('lesson_id', $i['lesson'])->whereNull('deleted_at')->orderBy('id')->get();
        $storyQuestions = DB::table('story_questions')->where('story_id', $i['story'])->whereNull('deleted_at')->orderBy('id')->get();

        $steps = [
            ['lesson test, all right', 'post', "/lessons/{$i['lesson']}/save/test", $this->answers($lessonQuestions, '', true)],
            ['lesson test, half wrong', 'post', "/lessons/{$i['lesson']}/save/test", $this->answers($lessonQuestions, '', false)],
            ['lesson tracking learn', 'post', "/lessons/{$i['lesson']}/tracking/learn", ['start_at' => '2026-10-22 09:40:00']],
            ['lesson tracking practise', 'post', "/lessons/{$i['lesson']}/tracking/practise", ['start_at' => '2026-10-22 09:45:00']],
            ['lesson tracking play', 'post', "/lessons/{$i['lesson']}/tracking/play", ['start_at' => '2026-10-22 09:47:00']],
            ['story test, all right', 'post', "/stories/{$i['story']}/save/test", $this->answers($storyQuestions, 'story_', true)],
            ['story test, half wrong', 'post', "/stories/{$i['story']}/save/test", $this->answers($storyQuestions, 'story_', false)],
            ['story tracking watching', 'post', "/stories/{$i['story']}/tracking/watching", ['start_at' => '2026-10-22 09:30:00']],
            ['story tracking reading', 'post', "/stories/{$i['story']}/tracking/reading", ['start_at' => '2026-10-22 09:35:00']],
        ];

        $n = 0;
        // the tests answered here: their ids differ between runs
        $newTests = [];
        $hideIds = function ($text) use (&$newTests) {
            foreach ($newTests as $id) {
                $text = preg_replace('#/' . $id . '\b#', '/NEW_TEST', $text);
            }
            return $text;
        };
        foreach ($steps as [$name, $method, $url, $data]) {
            $n++;
            $this->actingAs($student, 'web');
            $max = [];
            foreach (self::WATCH as $t) {
                $max[$t] = $t === 'notifications' ? DB::table($t)->count() : (int) DB::table($t)->max('id');
            }
            $assignments = DB::table('user_assignments')->where('user_id', $student->id)->orderBy('id')->get()->toJson();
            $storyAssignments = DB::table('user_story_assignments')->where('user_id', $student->id)->orderBy('id')->get()->toJson();
            $response = $this->call(strtoupper($method), $url, $data);
            $out = "STEP $name\nDATA " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n" . ResponseSnapshotTest::describe($response) . "\n";
            foreach (self::WATCH as $t) {
                if ($t === 'notifications') {
                    $out .= 'NOTIFICATIONS ADDED ' . (DB::table($t)->count() - $max[$t]) . "\n";
                    continue;
                }
                foreach (DB::table($t)->where('id', '>', $max[$t])->orderBy('id')->get() as $row) {
                    if (in_array($t, ['user_tests', 'student_story_tests'], true)) {
                        $newTests[] = $row->id;
                    }
                    $row = (array) $row;
                    foreach (['id', 'created_at', 'updated_at', 'user_test_id', 'student_story_test_id'] as $k) {
                        unset($row[$k]);
                    }
                    $out .= "NEW $t " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
                }
            }
            $out .= 'USER ASSIGNMENTS CHANGED ' . json_encode($assignments !== DB::table('user_assignments')->where('user_id', $student->id)->orderBy('id')->get()->toJson()) . "\n";
            $out .= 'USER STORY ASSIGNMENTS CHANGED ' . json_encode($storyAssignments !== DB::table('user_story_assignments')->where('user_id', $student->id)->orderBy('id')->get()->toJson()) . "\n";
            $out .= 'TEACHER ' . json_encode(DB::table('teachers')->where('id', $teacher->id)->first(['pending_tasks', 'corrected_tasks', 'returned_tasks', 'passed_tests', 'failed_tests'])) . "\n";
            file_put_contents(sprintf('%s/student_%02d_%s.txt', $dir, $n, preg_replace('/[^A-Za-z0-9]+/', '_', $name)), $hideIds($out));
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        // what the student and the teacher see afterwards
        $test = DB::table('user_tests')->where('user_id', $student->id)->orderByDesc('id')->value('id');
        $storyTest = DB::table('student_story_tests')->where('user_id', $student->id)->orderByDesc('id')->value('id');
        foreach ([
            ['web', $student, '/lessons/certificates'], ['web', $student, "/lessons/certificates-answers/$test"],
            ['web', $student, '/stories/certificates'], ['web', $student, "/stories/certificates-answers/$storyTest"],
            ['web', $student, '/home'], ['web', $student, '/ranking'],
            ['teacher', $teacher, "/teacher/lessons_tests/preview_answers/$test"], ['teacher', $teacher, "/teacher/lessons_tests/correcting/$test"],
            ['teacher', $teacher, "/teacher/stories_tests/correcting/$storyTest"], ['teacher', $teacher, "/teacher/stories_tests/$storyTest/certificate"],
            ['teacher', $teacher, '/teacher/home'],
        ] as $k => [$guard, $user, $url]) {
            $this->actingAs($user, $guard);
            $response = $this->get($url);
            // the new test ids differ between runs: name the files without them
            $label = str_replace([(string) $test, (string) $storyTest], ['TEST', 'STORYTEST'], "$guard $url");
            file_put_contents(sprintf('%s/student_page_%02d_%s.txt', $dir, $k, preg_replace('/[^A-Za-z0-9]+/', '_', $label)),
                $hideIds(ResponseSnapshotTest::describe($response)));
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        $this->assertTrue(true);
    }
}
