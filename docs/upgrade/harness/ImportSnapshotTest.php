<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): imports student files (xlsx and a
 * UTF-8 csv with Arabic names; valid rows, wrong rows, an email already used
 * in the same school and in another one), then the same students again in
 * "update" mode, and a teachers file, from the manager panel. Writes the
 * answer, the import_files row, its logs and the students and teachers it
 * created to SNAPSHOT_DIR. The rows are rolled back and the uploaded copies
 * deleted.
 */
class ImportSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }

    private function sheet(array $rows, $type, $name)
    {
        $book = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $book->getActiveSheet()->fromArray($rows, null, 'A1', true);
        $path = sys_get_temp_dir() . '/' . $name;
        if ($type === 'csv') {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($book);
            $writer->setUseBOM(false);
        } else {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($book);
        }
        $writer->save($path);

        return new UploadedFile($path, $name, $type === 'csv' ? 'text/csv' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }

    public function test_imports()
    {
        // the same "now" on both versions and on both sides of midnight
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');
        $dir = getenv('SNAPSHOT_DIR');
        @mkdir($dir, 0777, true);
        $manager = SmokeRoutesTest::manager();
        $i = ParamPagesSnapshotTest::ids();
        $teacher = SmokeRoutesTest::teacher();
        $other = DB::table('users')->where('school_id', '!=', $i['school'])->whereNull('deleted_at')->orderBy('id')->value('email');
        $same = DB::table('users')->where('school_id', $i['school'])->whereNull('deleted_at')->where('archived', 0)->orderBy('id')->value('email');

        $heading = ['Student ID', 'Name', 'Email', 'Mobile', 'Password', 'Grade', 'Alternative Grade', 'Section', 'Learning Years', 'Gender', 'Active', 'Nationality', 'Birth Date', 'Teacher'];
        $rows = [
            $heading,
            ['UPG-1', 'Upgrade First Student', 'upgrade-import-1@example.invalid', '0501111111', 'secret1', 3, null, 'A', 2, 'Boy', 1, 'UAE', '2015-01-01', $teacher->email],
            ['UPG-2', 'Upgrade Second Student', null, null, null, 4, 5, 'B', 3, 'Girl', null, null, null, null],
            ['UPG-3', 'Upgrade Bad Grade', 'upgrade-import-3@example.invalid', null, null, 99, null, null, 2, 'Boy', 1, null, null, null],
            ['UPG-4', '', 'upgrade-import-4@example.invalid', null, null, 3, null, null, 2, 'Boy', 1, null, null, null],
            ['UPG-5', 'Upgrade Other School', $other, null, null, 15, null, null, 1, 'Girl', 1, null, null, null],
            ['UPG-6', 'Upgrade Same School', $same, null, null, 2, null, null, 1, 'Boy', 1, null, null, null],
        ];
        $arabic = [
            $heading,
            ['UPG-7', 'طالب الفحص الأول', 'upgrade-import-7@example.invalid', null, null, 3, null, 'أ', 2, 'Boy', 1, 'الإمارات', null, null],
            ['UPG-8', 'طالبة الفحص الثانية', null, null, null, 5, null, 'ب', 3, 'Girl', 1, null, null, null],
        ];
        $base = [
            'type' => 'User', 'school_id' => $i['school'], 'package_id' => $i['package'], 'active_to' => '2027-06-30',
            'back_grade' => 0, 'last_of_email' => '@upgrade.invalid', 'year_id' => $i['year'],
        ];
        $calls = [
            ['students-xlsx-create', $base + ['process_type' => 'create'], $this->sheet($rows, 'xlsx', 'upgrade-students.xlsx')],
            ['students-csv-create', $base + ['process_type' => 'create'], $this->sheet($arabic, 'csv', 'upgrade-students.csv')],
            ['students-xlsx-update', $base + ['process_type' => 'update'], $this->sheet([
                $heading,
                ['UPG-1', 'Upgrade First Student Edited', null, '0509999999', null, 4, null, 'C', 3, 'Boy', 0, null, null, null],
                ['UPG-404', 'Nobody', null, null, null, 4, null, null, 3, 'Boy', 1, null, null, null],
            ], 'xlsx', 'upgrade-students-update.xlsx')],
            ['students-back-grade', array_merge($base, ['process_type' => 'create', 'back_grade' => 1]), $this->sheet([
                $heading,
                ['UPG-9', 'Upgrade Back Grade', null, null, null, 4, 15, null, 2, 'Boy', 1, null, null, null],
            ], 'xlsx', 'upgrade-students-back.xlsx')],
            ['teachers-xlsx', ['type' => 'Teacher', 'school_id' => $i['school'], 'active_to' => '2027-06-30', 'last_of_email' => '@upgrade.invalid'], $this->sheet([
                ['Name', 'Email', 'Mobile'],
                ['Upgrade Teacher One', 'upgrade-teacher-1@example.invalid', '0501234567'],
                ['Upgrade Teacher Two', null, null],
                ['', 'upgrade-teacher-3@example.invalid', null],
            ], 'xlsx', 'upgrade-teachers.xlsx')],
            ['missing-fields', ['type' => 'User'], null],
        ];

        $uploaded = [];
        foreach ($calls as [$name, $data, $file]) {
            $this->actingAs($manager, 'manager');
            $maxFile = (int) DB::table('import_files')->max('id');
            $maxUser = (int) DB::table('users')->max('id');
            $maxTeacher = (int) DB::table('teachers')->max('id');
            $files = $file ? ['import_file' => $file] : [];
            $response = $this->call('POST', '/manager/import_files', $data, [], $files);
            $out = "IMPORT $name\n" . ResponseSnapshotTest::describe($response) . "\n";
            foreach (DB::table('import_files')->where('id', '>', $maxFile)->orderBy('id')->get() as $row) {
                $row = (array) $row;
                $uploaded[] = $row['file_path'];
                $logs = DB::table('import_file_logs')->where('import_file_id', $row['id'])->orderBy('row_num')->get()
                    ->map(function ($l) { return [$l->row_num, json_decode($l->data, true)]; })->all();
                unset($row['id'], $row['file_name'], $row['file_path'], $row['created_at'], $row['updated_at']);
                $out .= 'FILE ' . json_encode($row, JSON_UNESCAPED_UNICODE) . "\nLOGS " . json_encode($logs, JSON_UNESCAPED_UNICODE) . "\n";
            }
            foreach (DB::table('users')->where('id', '>', $maxUser)->orderBy('id')->get() as $row) {
                $out .= 'NEW STUDENT ' . json_encode($this->clean((array) $row), JSON_UNESCAPED_UNICODE) . "\n";
            }
            foreach (DB::table('users')->whereIn('id_number', ['UPG-1', 'UPG-6'])->where('school_id', $i['school'])->where('id', '<=', $maxUser)->orderBy('id')->get() as $row) {
                $out .= 'EXISTING STUDENT ' . json_encode($this->clean((array) $row), JSON_UNESCAPED_UNICODE) . "\n";
            }
            foreach (DB::table('teachers')->where('id', '>', $maxTeacher)->orderBy('id')->get() as $row) {
                $out .= 'NEW TEACHER ' . json_encode($this->clean((array) $row), JSON_UNESCAPED_UNICODE) . "\n";
            }
            $out .= 'TEACHER STUDENTS ' . DB::table('teacher_students')->where('teacher_id', $teacher->id)->whereIn('user_id', DB::table('users')->where('id', '>', $maxUser)->pluck('id'))->count() . "\n";
            // generated emails end in a random number (UserImport::generateEmail())
            $out = preg_replace('/-\d+@upgrade\.invalid/', '-N@upgrade.invalid', $out);
            file_put_contents("$dir/import_$name.txt", $out);
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        foreach ($uploaded as $path) {
            if ($path && is_file(public_path($path))) {
                unlink(public_path($path));
            }
        }
        $this->assertTrue(true);
    }

    private function clean(array $row)
    {
        foreach (['id', 'created_at', 'updated_at', 'remember_token', 'import_file_id', 'import_student_file_id'] as $k) {
            unset($row[$k]);
        }
        foreach ($row as $k => $v) {
            if (is_string($v) && preg_match('/^\$2y\$/', $v)) {
                $row[$k] = 'HASH';
            }
        }

        return $row;
    }
}
