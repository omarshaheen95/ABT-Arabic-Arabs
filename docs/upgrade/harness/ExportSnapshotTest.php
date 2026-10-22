<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): runs the Excel exports of the
 * manager panel (filtered to the school of SmokeRoutesTest where they take a
 * school) and the same General exports from the school, teacher and
 * supervisor panels, plus the student cards pages, and writes every cell value
 * of the produced workbooks to SNAPSHOT_DIR, so two versions can be diffed.
 */
class ExportSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }

    public function test_exports()
    {
        // the same "now" on both versions and on both sides of midnight
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');
        $dir = getenv('SNAPSHOT_DIR');
        @mkdir($dir, 0777, true);

        $users = [
            'manager' => SmokeRoutesTest::manager(),
            'school' => SmokeRoutesTest::school(),
            'teacher' => SmokeRoutesTest::teacher(),
            'supervisor' => SmokeRoutesTest::supervisor(),
        ];
        $ids = ParamPagesSnapshotTest::ids();
        $school = ['school_id' => $ids['school']];
        $dates = ['start_date' => '2024-09-01', 'end_date' => '2025-06-30'];

        $calls = [];
        foreach ([
            'export_schools' => [], 'manager/export' => [], 'export_levels' => [], 'export_stories' => [],
            'export_lessons' => [], 'export_hidden_lessons' => [], 'export_hidden_story' => [], 'export_packages' => [],
            'import_files_export_data' => [], 'login_sessions/export' => ['model_type' => \App\Models\School::class],
        ] as $uri => $data) {
            $calls[] = ['manager', 'post', "/manager/$uri", $data];
        }
        $general = [
            'supervisor/export' => $school, 'teacher/export' => $school, 'tracking_teachers_export' => $school + $dates,
            'user/export' => $school, 'lessons_tests/export' => $school, 'stories_tests/export' => $school,
            'stories_records/export' => $school, 'students_works/export' => $school,
            'lesson_assignment/export' => $school, 'user_lesson_assignment/export' => $school,
            'story_assignment/export' => $school, 'user_story_assignment/export' => $school,
            'motivational_certificates/export' => $school, 'usage_report_excel' => $school + $dates,
        ];
        foreach (SmokeRoutesTest::PANELS as $panel) {
            foreach ($general as $uri => $data) {
                // the other panels are already limited to their own school
                $calls[] = [$panel, 'post', "/$panel/$uri", $panel === 'manager' ? $data : $dates];
            }
            $calls[] = [$panel, 'get', "/$panel/user/cards_and_qr", $school + ['grade' => $ids['grade']]];
            $calls[] = [$panel, 'post', "/$panel/user/cards_by_section", $school + ['grade' => $ids['grade']]];
        }

        foreach ($calls as [$guard, $method, $url, $data]) {
            $this->actingAs($users[$guard], $guard);
            $response = $method === 'post' ? $this->post($url, $data) : $this->get($url . '?' . http_build_query($data));
            $out = "URL $method $url " . json_encode($data) . "\nSTATUS " . $response->getStatusCode() . "\n";
            try {
                $e = $response->exception;
            } catch (\Throwable $ignored) {
                $e = null;
            }
            if ($e) {
                $out .= 'EXCEPTION ' . get_class($e) . ': ' . strtok($e->getMessage(), "\n") . "\n";
            }
            $base = $response->baseResponse;
            if ($base instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
                $out .= 'FILE ' . $response->headers->get('Content-Disposition') . "\n";
                $book = \PhpOffice\PhpSpreadsheet\IOFactory::load($base->getFile()->getPathname());
                foreach ($book->getAllSheets() as $sheet) {
                    $out .= "SHEET " . $sheet->getTitle() . ' ' . $sheet->getHighestColumn() . $sheet->getHighestRow() . "\n";
                    foreach ($sheet->toArray(null, false, false, false) as $row) {
                        $out .= json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
                    }
                }
            } else {
                $out .= substr(ResponseSnapshotTest::describe($response), 0, 400000) . "\n";
            }
            $name = $guard . '_' . trim(preg_replace('/[^A-Za-z0-9]+/', '_', $method . '_' . $url), '_');
            file_put_contents("$dir/export_$name.txt", $out);
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        $this->assertTrue(true);
    }
}
