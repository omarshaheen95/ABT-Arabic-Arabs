<?php

namespace App\Reports;

use App\Models\School;
use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\StudentTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserRecord;
use App\Models\UserTest;
use App\Models\UserTracker;
use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsageReport
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function report()
    {
        $request = $this->request;
        $year = cache()->remember("year_{$request->year_id}", 3600, function () use ($request) {
            return Year::find($request->year_id);
        });

        $school_ids = $request->get('school_id');
        $schools = School::select('id', 'name', 'logo')
            ->when(is_array($school_ids),  function($q) use ($school_ids){  $q->whereIn('id', $school_ids);})
            ->when(!is_array($school_ids),  function($q) use ($school_ids){  $q->where('id', $school_ids);})
            ->get();

        foreach ($schools as $school) {
            $school->logo = $school->logo ? asset($school->logo) : asset('assets/media/icons/school.png');
            $school->name = $school->name;
        }

        $selected_grades = $request->get('grades', []);
        $sysGrade = [15, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $grades = array_intersect($selected_grades, $sysGrade);

        $start_date = $request->get('start_date', false);
        $end_date = $request->get('end_date', false);

        $guard = getGuard();
        $guard_user = Auth::guard($guard)->user();

        // Total students
        $data['total_students'] = User::query()
            ->filterByGradeAndYear($grades, $year)
            ->filterBySchools($schools)
            ->filterByGuard($guard, $guard_user)
            ->count();

        if ($guard != 'teacher') {
            // Total teachers
            $data['total_teachers'] = Teacher::query()
                ->filterBySchools($schools)
                ->filterBySupervisor($guard, $guard_user)
                ->count();

            // Top teacher
            $data['top_teacher'] = Teacher::query()
                ->filterBySchools($schools)
                ->filterBySupervisor($guard, $guard_user)
                ->orderBy('passed_tests', 'desc')
                ->first();
        }


        // Top student
        $data['top_student'] = User::query()
            ->filterByGradeAndYear($grades, $year)
            ->filterBySchools($schools)
            ->filterByGuard($guard, $guard_user)
            ->filterByDateRange($start_date, $end_date)
            ->withCount(['user_test' => function($q){ $q->where('status', 'Pass');}])
            ->orderBy('user_test_count', 'desc')
            ->first();

        // Student test statistics
        $testStats = UserTest::query()
            ->selectRaw('status, COUNT(*) as count')
            ->filterByUsers($schools, $grades, $year, $guard, $guard_user)
            ->filterByDateRange($start_date, $end_date)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $data['total_tests'] = $testStats->sum();
        $data['total_pass_tests'] = $testStats->get('Pass', 0);
        $data['total_fail_tests'] = $testStats->get('Fail', 0);

        // Student story test statistics
        $storyTestStats = StudentStoryTest::query()
            ->selectRaw('status, COUNT(*) as count')
            ->filterByUsers($schools, $grades, $year, $guard, $guard_user)
            ->filterByDateRange($start_date, $end_date)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $data['total_story_tests'] = $storyTestStats->sum();
        $data['total_pass_story_tests'] = $storyTestStats->get('Pass', 0);
        $data['total_fail_story_tests'] = $storyTestStats->get('Fail', 0);

        // Assignments statistics
        $assignmentStats = UserLesson::query()
            ->selectRaw('status, COUNT(*) as count')
            ->filterByUsers($schools, $grades, $year, $guard, $guard_user)
            ->filterByDateRange($start_date, $end_date)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $data['total_assignments'] = $assignmentStats->sum();
        $data['total_corrected_assignments'] = $assignmentStats->get('corrected', 0);
        $data['total_uncorrected_assignments'] = $assignmentStats->only(['pending', 'returned'])->sum();

        $data['stories_recorde'] = StoryUserRecord::query()
            ->filterByUsers($schools, $grades, $year, $guard, $guard_user) // استخدام السكوب لتصفية المستخدمين
            ->filterByDateRange($start_date, $end_date) // فلترة حسب التاريخ
            ->count();

        $data['corrected_stories_recorde'] = StoryUserRecord::query()
            ->filterByUsers($schools, $grades, $year, $guard, $guard_user) // استخدام السكوب لتصفية المستخدمين
            ->filterByDateRange($start_date, $end_date) // فلترة حسب التاريخ
            ->where('status', 'corrected') // فلترة حسب الحالة
            ->count();

        $data['uncorrected_stories_recorde'] = StoryUserRecord::query()
            ->filterByUsers($schools, $grades, $year, $guard, $guard_user) // استخدام السكوب لتصفية المستخدمين
            ->filterByDateRange($start_date, $end_date) // فلترة حسب التاريخ
            ->whereIn('status', ['pending', 'returned']) // فلترة حسب الحالات المطلوبة
            ->count();

        if ($guard != 'teacher') {
            $teachers = Teacher::query()
                ->whereIn('school_id', $schools->pluck('id'))
                ->when($guard == 'supervisor', function (Builder $query) use ($guard_user) {
                    $query->whereHas('supervisor_teachers', function (Builder $query) use ($guard_user) {
                        $query->where('supervisor_id', $guard_user->id);
                    });
                })
                ->get();

            foreach ($teachers as $teacher) {
                $teacher_story_tests_statistics = StudentStoryTest::query()
                    ->whereHas('user', function (Builder $query) use ($schools, $grades, $year, $teacher, $guard, $guard_user) {
                        $query->where(function (Builder $query) use ($grades) {
                            $query->whereIn('grade_id', $grades)
                                ->orWhereIn('alternate_grade_id', $grades);
                        })
                            ->where('year_id', $year->id)
                            ->whereIn('school_id', $schools->pluck('id'))
                            ->whereHas('teacher_student', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->when($guard == 'teacher', function (Builder $query) use ($guard_user) {
                                $query->whereHas('teacher_student', function (Builder $query) use ($guard_user) {
                                    $query->where('teacher_id', $guard_user->id);
                                });
                            })
                            ->when($guard == 'supervisor', function (Builder $query) use ($guard_user) {
                                $query->whereHas('teacher_student', function (Builder $query) use ($guard_user) {
                                    $query->whereHas('teacher', function (Builder $query) use ($guard_user) {
                                        $query->whereHas('supervisor_teachers', function (Builder $query) use ($guard_user) {
                                            $query->where('supervisor_id', $guard_user->id);
                                        });
                                    });
                                });
                            });
                    })
                    ->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->get();
                $teacher->failed_story_tests_statictics = $teacher_story_tests_statistics->where('status', 'Fail')->count();
                $teacher->passed_story_tests_statictics = $teacher_story_tests_statistics->where('status', 'Pass')->count();

                $teacher_tests_statistics = UserTest::query()
                    ->whereHas('user', function (Builder $query) use ($schools, $grades, $year, $teacher, $guard, $guard_user) {
                        $query->where(function (Builder $query) use ($grades) {
                            $query->whereIn('grade_id', $grades)
                                ->orWhereIn('alternate_grade_id', $grades);
                        })
                            ->where('year_id', $year->id)
                            ->whereIn('school_id', $schools->pluck('id'))
                            ->whereHas('teacher_student', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->when($guard == 'teacher', function (Builder $query) use ($guard_user) {
                                $query->whereHas('teacher_student', function (Builder $query) use ($guard_user) {
                                    $query->where('teacher_id', $guard_user->id);
                                });
                            })
                            ->when($guard == 'supervisor', function (Builder $query) use ($guard_user) {
                                $query->whereHas('teacher_student', function (Builder $query) use ($guard_user) {
                                    $query->whereHas('teacher', function (Builder $query) use ($guard_user) {
                                        $query->whereHas('supervisor_teachers', function (Builder $query) use ($guard_user) {
                                            $query->where('supervisor_id', $guard_user->id);
                                        });
                                    });
                                });
                            });
                    })
                    ->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->get();

                $teacher->failed_tests_statictics = $teacher_tests_statistics->where('status', 'Fail')->count();

                $teacher->passed_tests_statictics = $teacher_tests_statistics->where('status', 'Pass')->count();

                $tasks_statistics = UserLesson::query()
                    ->whereHas('user', function (Builder $query) use ($schools, $grades, $year, $teacher, $guard, $guard_user) {
                        $query->where(function (Builder $query) use ($grades, $teacher) {
                            $query->whereIn('grade_id', $grades)
                                ->orWhereIn('alternate_grade_id', $grades);
                        })
                            ->where('year_id', $year->id)
                            ->whereIn('school_id', $schools->pluck('id'))
                            ->whereHas('teacher_student', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->when($guard == 'teacher', function (Builder $query) use ($guard_user) {
                                $query->whereHas('teacher_student', function (Builder $query) use ($guard_user) {
                                    $query->where('teacher_id', $guard_user->id);
                                });
                            })
                            ->when($guard == 'supervisor', function (Builder $query) use ($guard_user) {
                                $query->whereHas('teacher_student', function (Builder $query) use ($guard_user) {
                                    $query->whereHas('teacher', function (Builder $query) use ($guard_user) {
                                        $query->whereHas('supervisor_teachers', function (Builder $query) use ($guard_user) {
                                            $query->where('supervisor_id', $guard_user->id);
                                        });
                                    });
                                });
                            });
                    })
                    ->whereDate('updated_at', '>=', $start_date)
                    ->whereDate('updated_at', '<=', $end_date)
                    ->get();

                $teacher->corrected_tasks_statistics = $tasks_statistics
                    ->where('status', 'corrected')
                    ->count();

                $teacher->returned_tasks_statistics = $tasks_statistics
                    ->where('status', 'returned')
                    ->count();

                $teacher->pending_tasks_statistics = $tasks_statistics
                    ->where('status', 'pending')
                    ->count();
            }
            $teachers = $teachers->chunk(20);
        }else{
            $teachers = [];
        }



        // User tracks statistics
        $tracks = UserTracker::query()
            ->filterByUsers($schools, $grades, $year, $guard, $guard_user)
            ->filterByDateRange($start_date, $end_date)
            ->get();

        $data['total_practice'] = $tracks->count();
        $data['learn'] = $tracks->where('type', 'learn')->count();
        $data['practise'] = $tracks->where('type', 'practise')->count();
        $data['test'] = $tracks->where('type', 'test')->count();

        $data['learn_avg'] = $data['total_practice'] > 0 ? ($data['learn'] / $data['total_practice']) * 100 : 0;
        $data['practise_avg'] = $data['total_practice'] > 0 ? ($data['practise'] / $data['total_practice']) * 100 : 0;
        $data['test_avg'] = $data['total_practice'] > 0 ? ($data['test'] / $data['total_practice']) * 100 : 0;

        // Grades data
        $grades_data = [];
        foreach ($grades as $grade) {
            $grades_data[$grade]['total_students'] = User::query()
                ->filterByGradeAndYear([$grade], $year)
                ->filterBySchools($schools)
                ->count();

            if ($guard != 'teacher') {
                $grades_data[$grade]['total_teachers'] = Teacher::query()
                    ->filterBySchools($schools)
                    ->filterByGrade($grade)
                    ->filterBySupervisor($guard, $guard_user)
                    ->count();

                $grades_data[$grade]['top_teacher'] = Teacher::query()
                    ->filterBySchools($schools)
                    ->filterByGrade($grade)
                    ->filterBySupervisor($guard, $guard_user)
                    ->orderBy('passed_tests', 'desc')
                    ->first();
            }

            $grades_data[$grade]['top_student_lesson'] = User::query()
                ->filterByGuard($guard, $guard_user) // استخدام سكوب لتصفية المستخدم بناءً على الحارس
                ->filterByGradeAndYear([$grade], $year) // استخدام سكوب لتصفية المستخدمين حسب الصف والسنة
                ->filterBySchools($schools) // استخدام سكوب لتصفية المستخدمين حسب المدارس
                ->withCount(['user_test' => function ($query) use ($start_date, $end_date, $grade) {
                    $query->where('status', 'Pass') // فقط الاختبارات الناجحة
                    ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date)
                        ->whereHas('lesson', function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade); // تصفية المستوى حسب الصف
                        });
                }])
                ->orderBy('user_test_count', 'desc') // ترتيب حسب عدد الاختبارات
                ->first();

            $grades_data[$grade]['top_student_story'] = User::query()
                ->filterByGuard($guard, $guard_user) // استخدام سكوب لتصفية المستخدم بناءً على الحارس
                ->filterByGradeAndYear([$grade], $year) // استخدام سكوب لتصفية المستخدمين حسب الصف والسنة
                ->filterBySchools($schools) // استخدام سكوب لتصفية المستخدمين حسب المدارس
                ->withCount(['user_story_tests' => function ($query) use ($start_date, $end_date, $grade) {
                    $query->where('status', 'Pass') // فقط اختبارات القصص الناجحة
                    ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date)
                        ->whereHas('story', function (Builder $query) use ($grade) {
                            $query->where('grade', $grade); // تصفية القصص حسب الصف
                        });
                }])
                ->orderBy('user_story_tests_count', 'desc') // ترتيب حسب عدد الاختبارات
                ->first();

            $lesson_test_statistics = UserTest::query()
                ->filterByUsers($schools, [$grade], $year, $guard, $guard_user) // استخدام السكوب لتصفية المستخدمين
                ->whereHas('lesson', function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade); // فلترة المستوى حسب الصف الدراسي
                })
                ->filterByDateRange($start_date, $end_date) // فلترة حسب التاريخ
                ->get();

            $grades_data[$grade]['total_tests'] = $lesson_test_statistics->count();
            $grades_data[$grade]['total_pass_tests'] = $lesson_test_statistics
                ->where('status', 'Pass')->count();
            $grades_data[$grade]['total_fail_tests'] = $lesson_test_statistics
                ->where('status', 'Fail')->count();

            $story_test_statistics = StudentStoryTest::query()
                ->filterByUsers($schools, [$grade], $year, $guard, $guard_user) // استخدام السكوب لتصفية المستخدمين
                ->whereHas('story', function (Builder $query) use ($grade) {
                    $query->where('grade', $grade); // فلترة القصص حسب الصف الدراسي
                })
                ->filterByDateRange($start_date, $end_date) // فلترة حسب التاريخ
                ->get();

            $grades_data[$grade]['total_story_tests'] = $story_test_statistics->count();
            $grades_data[$grade]['total_story_pass_tests'] = $story_test_statistics
                ->where('status', 'Pass')->count();
            $grades_data[$grade]['total_story_fail_tests'] = $story_test_statistics
                ->where('status', 'Fail')->count();

            $assignments_statistics = UserLesson::query()
                ->filterByUsers($schools, [$grade], $year, $guard, $guard_user) // استخدام السكوب لتصفية المستخدمين
                ->filterByDateRange($start_date, $end_date) // فلترة حسب التاريخ
                ->get();

            $grades_data[$grade]['total_assignments'] = $assignments_statistics->count();
            $grades_data[$grade]['total_corrected_assignments'] = $assignments_statistics
                ->where('status', 'corrected')->count();
            $grades_data[$grade]['total_uncorrected_assignments'] = $assignments_statistics
                ->where('status', 'pending')->count();
            $grades_data[$grade]['total_returned_assignments'] = $assignments_statistics
                ->where('status', 'returned')->count();

            $tracks = UserTracker::query()
                ->filterByUsers($schools, [$grade], $year, $guard, $guard_user) // استخدام سكوب لتصفية المستخدمين
                ->whereHas('lesson', function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade); // فلترة المستوى حسب الصف
                })
                ->filterByDateRange($start_date, $end_date) // فلترة حسب التاريخ
                ->latest()
                ->get();

            if ($grades_data[$grade]['total_practice'] = $tracks->count()) {
                $grades_data[$grade]['learn'] = $tracks->where('type', 'learn')->count();
                $grades_data[$grade]['practise'] = $tracks->where('type', 'practise')->count();
                $grades_data[$grade]['test'] = $tracks->where('type', 'test')->count();
                $grades_data[$grade]['play'] = $tracks->where('type', 'play')->count();

                $grades_data[$grade]['learn_avg'] = ($grades_data[$grade]['learn'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['practise_avg'] = ($grades_data[$grade]['practise'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['test_avg'] = ($grades_data[$grade]['test'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['play_avg'] = ($grades_data[$grade]['play'] / $grades_data[$grade]['total_practice']) * 100;
            } else {
                // القيم الافتراضية في حال عدم وجود نتائج
                $grades_data[$grade] = array_merge($grades_data[$grade], [
                    'total_practice' => 0,
                    'learn' => 0,
                    'practise' => 0,
                    'test' => 0,
                    'play' => 0,
                    'learn_avg' => 0,
                    'practise_avg' => 0,
                    'test_avg' => 0,
                    'play_avg' => 0,
                ]);
            }

        }

        return view('general.reports.usage_report.usage_report', compact('grades', 'grades_data', 'data', 'schools', 'start_date', 'end_date', 'year', 'teachers'));
    }
}
