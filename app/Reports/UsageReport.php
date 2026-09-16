<?php

namespace App\Reports;

use App\Models\School;
use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\UserTracker;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Usage report.
 *
 * Every figure is produced by a grouped aggregate query that joins the fact
 * table to `users` once, instead of running one query per grade / per teacher.
 * The per grade breakdown is derived from the same aggregate rows, so the grade
 * pages always add up to the overall page.
 */
class UsageReport
{
    /** Grades the report knows about, in display order (13 = KG). */
    const SYSTEM_GRADES = [13, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

    /** How many teachers fit on one chart page. */
    const TEACHERS_PER_CHART = 20;

    private $request;

    /** @var Year */
    private $year;

    /** @var array<int> */
    private $school_ids = [];

    /** @var array<int> */
    private $grades = [];

    /** @var Carbon|null */
    private $start_date;

    /** @var Carbon|null */
    private $end_date;

    private $guard;

    private $guard_user;

    /** Optional teacher filter coming from the supervisor screen. */
    private $teacher_id;

    /** Whether students whose subscription lapsed are still reported on. */
    private $include_archived = true;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function report()
    {
        $this->prepare();

        $schools = School::query()
            ->select('id', 'name', 'logo')
            ->whereIn('id', $this->school_ids)
            ->get();

        foreach ($schools as $school) {
            $school->logo = $school->logo ? asset($school->logo) : asset('assets/media/icons/school.png');
        }

        $year = $this->year;
        $start_date = $this->start_date ? $this->start_date->format('Y-m-d') : null;
        $end_date = $this->end_date ? $this->end_date->format('Y-m-d') : null;
        $include_archived = $this->include_archived;

        $students = $this->studentBreakdown();
        $lesson_tests = $this->breakdown(UserTest::class, 'status');
        $story_tests = $this->breakdown(StudentStoryTest::class, 'status');
        $assignments = $this->breakdown(UserLesson::class, 'status');
        $story_records = $this->breakdown(StoryUserRecord::class, 'status');
        $tracks = $this->breakdown(UserTracker::class, 'type');

        $top_lesson_students = $this->topStudents(UserTest::class);
        $top_story_students = $this->topStudents(StudentStoryTest::class);
        $students_index = $this->loadStudents(array_merge($top_lesson_students, $top_story_students));

        $data = [
            'total_students' => $students['total'],

            'top_student' => $students_index->get(Arr::get($top_lesson_students, 'all')),

            'total_tests' => $lesson_tests['total'],
            'total_pass_tests' => $this->bucket($lesson_tests, 'Pass'),
            'total_fail_tests' => $this->bucket($lesson_tests, 'Fail'),
            'total_unmarked_tests' => $this->unmarked($lesson_tests),

            'total_story_tests' => $story_tests['total'],
            'total_pass_story_tests' => $this->bucket($story_tests, 'Pass'),
            'total_fail_story_tests' => $this->bucket($story_tests, 'Fail'),

            'total_assignments' => $assignments['total'],
            'total_corrected_assignments' => $this->bucket($assignments, 'corrected'),
            'total_uncorrected_assignments' => $this->bucket($assignments, 'pending')
                + $this->bucket($assignments, 'returned'),

            'stories_recorde' => $story_records['total'],
            'corrected_stories_recorde' => $this->bucket($story_records, 'corrected'),
            'uncorrected_stories_recorde' => $this->bucket($story_records, 'pending')
                + $this->bucket($story_records, 'returned'),
        ];

        $data += $this->trackRates($tracks);

        // Teachers: needed for the overall counters, the "high performance"
        // rows and the two bar charts.
        $teacher_grades = [];
        $teacher_lesson_tests = [];
        $teacher_story_tests = [];
        $teacher_models = collect();
        $teachers = [];

        if ($this->guard != 'teacher') {
            $teacher_grades = $this->teacherGradeMap();
            $teacher_lesson_tests = $this->teacherBreakdown(UserTest::class);
            $teacher_story_tests = $this->teacherBreakdown(StudentStoryTest::class);

            $teacher_models = $this->loadTeachers(Arr::get($teacher_grades, 'all', []));

            $data['total_teachers'] = count(Arr::get($teacher_grades, 'all', []));
            $data['top_teacher'] = $teacher_models->get(
                $this->topTeacher(Arr::get($teacher_grades, 'all', []), $teacher_lesson_tests, $teacher_story_tests)
            );

            foreach ($teacher_models as $teacher) {
                $teacher->passed_tests_statictics = $this->teacherBucket($teacher_lesson_tests, $teacher->id, 'Pass');
                $teacher->failed_tests_statictics = $this->teacherBucket($teacher_lesson_tests, $teacher->id, 'Fail');
                $teacher->passed_story_tests_statictics = $this->teacherBucket($teacher_story_tests, $teacher->id, 'Pass');
                $teacher->failed_story_tests_statictics = $this->teacherBucket($teacher_story_tests, $teacher->id, 'Fail');
            }

            $teachers = $teacher_models->values()->chunk(self::TEACHERS_PER_CHART);
        }

        // Per grade pages. Grades without students are left out entirely.
        $grades_data = [];
        foreach ($this->grades as $grade) {
            $total_students = Arr::get($students, "grades.$grade.total", 0);

            if ($total_students == 0) {
                continue;
            }

            $grade_data = [
                'total_students' => $total_students,

                'top_student_lesson' => $students_index->get(Arr::get($top_lesson_students, $grade)),
                'top_student_story' => $students_index->get(Arr::get($top_story_students, $grade)),

                'total_tests' => Arr::get($lesson_tests, "grades.$grade.total", 0),
                'total_pass_tests' => $this->bucket($lesson_tests, 'Pass', $grade),
                'total_fail_tests' => $this->bucket($lesson_tests, 'Fail', $grade),
                'total_unmarked_tests' => $this->unmarked($lesson_tests, $grade),

                'total_story_tests' => Arr::get($story_tests, "grades.$grade.total", 0),
                'total_story_pass_tests' => $this->bucket($story_tests, 'Pass', $grade),
                'total_story_fail_tests' => $this->bucket($story_tests, 'Fail', $grade),

                'total_assignments' => Arr::get($assignments, "grades.$grade.total", 0),
                'total_corrected_assignments' => $this->bucket($assignments, 'corrected', $grade),
                'total_uncorrected_assignments' => $this->bucket($assignments, 'pending', $grade),
                'total_returned_assignments' => $this->bucket($assignments, 'returned', $grade),
            ];

            if ($this->guard != 'teacher') {
                $grade_teachers = Arr::get($teacher_grades, $grade, []);
                $grade_data['total_teachers'] = count($grade_teachers);
                $grade_data['top_teacher'] = $teacher_models->get(
                    $this->topTeacher($grade_teachers, $teacher_lesson_tests, $teacher_story_tests, $grade)
                );
            }

            $grades_data[$grade] = $grade_data + $this->trackRates($tracks, $grade);
        }

        $grades = $this->grades;

        return view('general.reports.usage_report.usage_report',
            compact('grades', 'grades_data', 'data', 'schools', 'start_date', 'end_date', 'year', 'teachers',
                'include_archived'));
    }

    /**
     * Read and normalise everything the report filters on.
     */
    private function prepare()
    {
        $request = $this->request;

        $this->year = Year::find($request->get('year_id'));

        $this->school_ids = array_values(array_filter(array_map(
            'intval', Arr::wrap($request->get('school_id'))
        )));

        $selected = array_map('intval', array_filter(Arr::wrap($request->get('grades', [])), 'is_numeric'));
        // Keep the system order (KG first) rather than the order the grades
        // happened to be picked in.
        $grades = array_values(array_intersect(self::SYSTEM_GRADES, $selected));
        // No grade picked (or only "all") means every grade.
        $this->grades = $grades ?: self::SYSTEM_GRADES;

        // The date range is optional: an empty range reports on the whole year.
        $this->start_date = $request->filled('start_date')
            ? Carbon::parse($request->get('start_date'))->startOfDay()
            : null;
        $this->end_date = $request->filled('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : null;

        // Archiving only blocks the student from logging in; the work they did
        // before that is still part of the school's usage. Defaults to true so
        // an older link without the parameter keeps reporting everything.
        $this->include_archived = $request->boolean('include_archived', true);

        $this->guard = getGuard();
        $this->guard_user = Auth::guard($this->guard)->user();

        // The teacher guard is already narrowed down by applyStudentGuard().
        $this->teacher_id = ($this->guard != 'teacher' && $request->filled('teacher_id'))
            ? $request->get('teacher_id')
            : null;
    }

    /**
     * The single place that decides which students exist for this report.
     *
     * Everything that reads students goes through here, so the aggregates and
     * the name lookups can never disagree about who is in scope.
     */
    private function usersQuery()
    {
        return User::query()->when($this->include_archived, function ($query) {
            $query->withoutGlobalScope('not_archived');
        });
    }

    /**
     * The students the report is about, as a sub query aliased `users`.
     *
     * It is built through Eloquent on purpose: that way every global scope on
     * the model (soft deletes, not_archived, and anything added later) applies
     * here as well. A plain `join('users', ...)` would silently skip those
     * scopes and the fact tables would then report on students that the
     * student counter excludes.
     */
    private function studentsSubQuery()
    {
        return $this->usersQuery()
            ->select('users.id', 'users.grade_id', 'users.alternate_grade_id')
            ->whereIn('users.school_id', $this->school_ids)
            ->where('users.year_id', $this->year->id)
            ->where(function ($query) {
                $query->whereIn('users.grade_id', $this->grades)
                    ->orWhereIn('users.alternate_grade_id', $this->grades);
            })
            ->toBase();
    }

    /**
     * Join a fact table to the students it belongs to and apply every filter.
     */
    private function applyStudentJoin($query, $table, $foreign = 'user_id')
    {
        $query->joinSub($this->studentsSubQuery(), 'users', 'users.id', '=', $table . '.' . $foreign);

        return $this->applyStudentGuard($query);
    }

    /**
     * Restrict the students to the ones the logged in user is allowed to see.
     */
    private function applyStudentGuard($query)
    {
        if ($this->guard === 'teacher') {
            $this->whereTeaches($query, $this->guard_user->id);
        }

        if ($this->guard === 'supervisor') {
            $query->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('teacher_users')
                    ->join('supervisor_teachers', 'supervisor_teachers.teacher_id', '=', 'teacher_users.teacher_id')
                    ->whereColumn('teacher_users.user_id', 'users.id')
                    ->whereNull('teacher_users.deleted_at')
                    ->whereNull('supervisor_teachers.deleted_at')
                    ->where('supervisor_teachers.supervisor_id', $this->guard_user->id);
            });
        }

        if ($this->teacher_id) {
            $this->whereTeaches($query, $this->teacher_id);
        }

        return $query;
    }

    private function whereTeaches($query, $teacher_id)
    {
        return $query->whereExists(function ($query) use ($teacher_id) {
            $query->select(DB::raw(1))
                ->from('teacher_users')
                ->whereColumn('teacher_users.user_id', 'users.id')
                ->whereNull('teacher_users.deleted_at')
                ->where('teacher_users.teacher_id', $teacher_id);
        });
    }

    /**
     * Compare against the raw datetime instead of DATE(created_at) so the
     * created_at index can be used.
     */
    private function applyDateRange($query, $column)
    {
        return $query->when($this->start_date, function ($query) use ($column) {
            $query->where($column, '>=', $this->start_date);
        })->when($this->end_date, function ($query) use ($column) {
            $query->where($column, '<=', $this->end_date);
        });
    }

    /**
     * One grouped query per fact table: rows of
     * (student grade, student alternate grade, bucket, count).
     */
    private function breakdown($model, $bucket_column)
    {
        $table = (new $model)->getTable();

        $query = $model::query()
            ->select('users.grade_id', 'users.alternate_grade_id')
            ->addSelect(DB::raw("`$table`.`$bucket_column` as bucket"))
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('users.grade_id', 'users.alternate_grade_id', $table . '.' . $bucket_column);

        $this->applyStudentJoin($query, $table);
        $this->applyDateRange($query, $table . '.created_at');

        return $this->fold($query->toBase()->get());
    }

    private function studentBreakdown()
    {
        $query = DB::query()
            ->fromSub($this->studentsSubQuery(), 'users')
            ->select('users.grade_id', 'users.alternate_grade_id')
            ->addSelect(DB::raw('NULL as bucket'))
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('users.grade_id', 'users.alternate_grade_id');

        $this->applyStudentGuard($query);

        return $this->fold($query->get());
    }

    /**
     * Turn the aggregate rows into an overall total plus a per grade total.
     *
     * A student that also has an alternate grade is reported under both grades,
     * which is how the student counters have always worked.
     */
    private function fold($rows)
    {
        $result = ['total' => 0, 'buckets' => [], 'grades' => []];

        foreach ($this->grades as $grade) {
            $result['grades'][$grade] = ['total' => 0, 'buckets' => []];
        }

        foreach ($rows as $row) {
            $count = (int) $row->aggregate;
            $bucket = $row->bucket;

            $result['total'] += $count;

            if ($bucket !== null) {
                $result['buckets'][$bucket] = Arr::get($result['buckets'], $bucket, 0) + $count;
            }

            foreach ($this->rowGrades($row) as $grade) {
                $result['grades'][$grade]['total'] += $count;

                if ($bucket !== null) {
                    $result['grades'][$grade]['buckets'][$bucket] =
                        Arr::get($result['grades'][$grade]['buckets'], $bucket, 0) + $count;
                }
            }
        }

        return $result;
    }

    /**
     * The reported grades of one aggregate row, without counting a student
     * twice when the alternate grade repeats the main one.
     */
    private function rowGrades($row)
    {
        $grades = [];

        foreach ([$row->grade_id, $row->alternate_grade_id] as $grade) {
            if ($grade === null) {
                continue;
            }

            $grade = (int) $grade;

            if (in_array($grade, $grades) || !in_array($grade, $this->grades)) {
                continue;
            }

            $grades[] = $grade;
        }

        return $grades;
    }

    /**
     * Tests that were submitted but carry no status yet, i.e. the teacher has
     * not marked them. Without this row "submitted" would not equal
     * "above" + "below" on the page.
     */
    private function unmarked(array $breakdown, $grade = null)
    {
        $total = $grade === null
            ? $breakdown['total']
            : Arr::get($breakdown, "grades.$grade.total", 0);

        return $total - $this->bucket($breakdown, 'Pass', $grade) - $this->bucket($breakdown, 'Fail', $grade);
    }

    private function bucket(array $breakdown, $bucket, $grade = null)
    {
        $path = $grade === null ? "buckets.$bucket" : "grades.$grade.buckets.$bucket";

        return Arr::get($breakdown, $path, 0);
    }

    /**
     * Learn / practise / assess-yourself split used by the pie charts.
     */
    private function trackRates(array $tracks, $grade = null)
    {
        $total = $grade === null
            ? $tracks['total']
            : Arr::get($tracks, "grades.$grade.total", 0);

        $data = [
            'total_practice' => $total,
            'learn' => $this->bucket($tracks, 'learn', $grade),
            'practise' => $this->bucket($tracks, 'practise', $grade),
            'test' => $this->bucket($tracks, 'test', $grade),
        ];

        foreach (['learn', 'practise', 'test'] as $type) {
            $data[$type . '_avg'] = $total > 0 ? ($data[$type] / $total) * 100 : 0;
        }

        return $data;
    }

    /**
     * Best performing student overall and per grade, by passed tests inside the
     * selected period.
     *
     * @return array keyed by grade plus an "all" entry, values are user ids
     */
    private function topStudents($model)
    {
        $table = (new $model)->getTable();

        $query = $model::query()
            ->select('users.id', 'users.grade_id', 'users.alternate_grade_id')
            ->selectRaw('COUNT(*) as aggregate')
            ->where($table . '.status', 'Pass')
            ->groupBy('users.id', 'users.grade_id', 'users.alternate_grade_id');

        $this->applyStudentJoin($query, $table);
        $this->applyDateRange($query, $table . '.created_at');

        $best = [];
        $counts = [];

        foreach ($query->toBase()->get() as $row) {
            $count = (int) $row->aggregate;

            foreach (array_merge(['all'], $this->rowGrades($row)) as $key) {
                if (!isset($counts[$key]) || $count > $counts[$key]) {
                    $counts[$key] = $count;
                    $best[$key] = (int) $row->id;
                }
            }
        }

        return $best;
    }

    private function loadStudents(array $ids)
    {
        $ids = array_values(array_unique(array_filter($ids)));

        if (empty($ids)) {
            return collect();
        }

        // Same archive decision as studentsSubQuery(): the aggregates and this
        // name lookup must agree, otherwise a top performer is found but cannot
        // be named and the cell renders empty.
        return $this->usersQuery()->whereIn('id', $ids)->get()->keyBy('id');
    }

    /**
     * Which teachers teach students of which grade. One query, folded into a
     * set of teacher ids per grade plus an "all" set.
     */
    private function teacherGradeMap()
    {
        $query = DB::table('teacher_users')
            ->select('teacher_users.teacher_id', 'users.grade_id', 'users.alternate_grade_id')
            ->join('teachers', 'teachers.id', '=', 'teacher_users.teacher_id')
            ->whereNull('teacher_users.deleted_at')
            ->whereNull('teachers.deleted_at')
            ->whereIn('teachers.school_id', $this->school_ids)
            ->groupBy('teacher_users.teacher_id', 'users.grade_id', 'users.alternate_grade_id');

        $this->applyStudentJoin($query, 'teacher_users');
        $this->applySupervisorTeacherScope($query);

        $map = ['all' => []];

        foreach ($this->grades as $grade) {
            $map[$grade] = [];
        }

        foreach ($query->get() as $row) {
            $teacher_id = (int) $row->teacher_id;

            foreach (array_merge(['all'], $this->rowGrades($row)) as $key) {
                $map[$key][$teacher_id] = $teacher_id;
            }
        }

        return array_map('array_values', $map);
    }

    /**
     * Passed / failed tests per teacher, and per teacher per grade.
     */
    private function teacherBreakdown($model)
    {
        $table = (new $model)->getTable();

        $query = $model::query()
            ->select('teacher_users.teacher_id', 'users.grade_id', 'users.alternate_grade_id')
            ->addSelect(DB::raw("`$table`.`status` as bucket"))
            ->selectRaw('COUNT(*) as aggregate');

        // `users` has to be joined before the joins that refer to it.
        $this->applyStudentJoin($query, $table);

        $query->join('teacher_users', function ($join) {
                $join->on('teacher_users.user_id', '=', 'users.id')
                    ->whereNull('teacher_users.deleted_at');
            })
            ->join('teachers', 'teachers.id', '=', 'teacher_users.teacher_id')
            ->whereNull('teachers.deleted_at')
            ->whereIn('teachers.school_id', $this->school_ids)
            ->groupBy('teacher_users.teacher_id', 'users.grade_id', 'users.alternate_grade_id', $table . '.status');

        $this->applySupervisorTeacherScope($query);
        $this->applyDateRange($query, $table . '.created_at');

        $result = ['all' => [], 'grades' => []];

        foreach ($query->toBase()->get() as $row) {
            $teacher_id = (int) $row->teacher_id;
            $count = (int) $row->aggregate;
            $bucket = $row->bucket;

            if ($bucket === null) {
                continue;
            }

            $result['all'][$teacher_id][$bucket] = Arr::get($result, "all.$teacher_id.$bucket", 0) + $count;

            foreach ($this->rowGrades($row) as $grade) {
                $result['grades'][$grade][$teacher_id][$bucket] =
                    Arr::get($result, "grades.$grade.$teacher_id.$bucket", 0) + $count;
            }
        }

        return $result;
    }

    private function applySupervisorTeacherScope($query)
    {
        if ($this->guard !== 'supervisor') {
            return $query;
        }

        return $query->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('supervisor_teachers')
                ->whereColumn('supervisor_teachers.teacher_id', 'teacher_users.teacher_id')
                ->whereNull('supervisor_teachers.deleted_at')
                ->where('supervisor_teachers.supervisor_id', $this->guard_user->id);
        });
    }

    private function teacherBucket(array $breakdown, $teacher_id, $bucket, $grade = null)
    {
        $path = $grade === null ? "all.$teacher_id.$bucket" : "grades.$grade.$teacher_id.$bucket";

        return Arr::get($breakdown, $path, 0);
    }

    /**
     * The teacher with the most passed tests (lessons + stories) in the period.
     */
    private function topTeacher(array $teacher_ids, array $lesson_tests, array $story_tests, $grade = null)
    {
        // Nobody is highlighted when no test was passed at all.
        $top = null;
        $best = 0;

        foreach ($teacher_ids as $teacher_id) {
            $passed = $this->teacherBucket($lesson_tests, $teacher_id, 'Pass', $grade)
                + $this->teacherBucket($story_tests, $teacher_id, 'Pass', $grade);

            if ($passed > $best) {
                $best = $passed;
                $top = $teacher_id;
            }
        }

        return $top;
    }

    private function loadTeachers(array $ids)
    {
        if (empty($ids)) {
            return collect();
        }

        return Teacher::query()
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->get()
            ->keyBy('id');
    }
}
