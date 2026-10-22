<?php

namespace Tests\Feature;

use App\Models\Manager;
use App\Models\School;
use App\Models\Supervisor;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Smoke test for the Laravel 13 upgrade.
 *
 * Opens every parameterless GET page of each guard against the local database
 * and compares the status with tests/smoke-baseline.json, which was recorded
 * on Laravel 8 before the upgrade. Pages that change data or build large files
 * on GET are skipped. Everything runs inside a transaction.
 *
 * Record a new baseline:  SMOKE_RECORD=1 php vendor/bin/phpunit --filter SmokeRoutesTest
 */
class SmokeRoutesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
        self::unlockAccounts();
    }

    /**
     * GET routes that must never run from a test: the public artisan routes of
     * routes/web.php (migrate, view, command runs schedule:work forever,
     * cache), the one-off routes of routes/manager.php that seed, give roles
     * or permissions, copy or rewrite data, or end in dd() (get_marks,
     * correct_tests), marking notifications read, exports and card sheets,
     * and logging out.
     */
    public const SKIP = '#^(migrate|view|command|cache)$|(^|/)(seed|give_roles|give_all_permissions|get_marks|correct_tests|copy_lessons|copy-teacher-data|remove_space|read_all|read-all|logout)(/|$)|export|excel|cards|_debugbar|log-viewer#i';

    private const BASELINE = 'tests/smoke-baseline.json';

    /** The panels that live under their own URL prefix, with their guard. */
    public const PANELS = ['manager', 'school', 'teacher', 'supervisor'];

    public function test_guest_pages()
    {
        $this->check('guest', null, self::guestFilter());
    }

    public function test_manager_pages()
    {
        $this->check('manager', self::manager(), self::panelFilter('manager'));
    }

    public function test_school_pages()
    {
        $this->check('school', self::school(), self::panelFilter('school'));
    }

    public function test_teacher_pages()
    {
        $this->check('teacher', self::teacher(), self::panelFilter('teacher'));
    }

    public function test_supervisor_pages()
    {
        $this->check('supervisor', self::supervisor(), self::panelFilter('supervisor'));
    }

    public function test_student_pages()
    {
        $this->check('web', self::student(), self::studentFilter());
    }

    /**
     * The accounts every upgrade check logs in with, picked the same way on
     * both versions: active and approved, but not by the forced password
     * change flag, which is raised on every supervisor and most teachers of
     * the local database. unlockAccounts() clears it for these five inside
     * the test's transaction.
     */
    public static function manager()
    {
        return Manager::query()
            ->where('active', 1)
            ->orderBy('id')
            ->get()
            ->sortByDesc(function ($manager) {
                return $manager->getAllPermissions()->count();
            })
            ->first();
    }

    /**
     * School 740: the active school whose supervisors have the most teachers
     * assigned, so every panel has real data to show (892 students; the next
     * one, 429, has 6,494 and makes some pages very slow).
     */
    public static function schoolId()
    {
        return (int) DB::table('supervisor_teachers')
            ->join('supervisors', 'supervisors.id', '=', 'supervisor_teachers.supervisor_id')
            ->join('schools', 'schools.id', '=', 'supervisors.school_id')
            ->where('schools.active', 1)
            ->where('supervisors.active', 1)->where('supervisors.approved', 1)
            ->whereNull('schools.deleted_at')->whereNull('supervisors.deleted_at')->whereNull('supervisor_teachers.deleted_at')
            ->groupBy('schools.id')
            ->orderByRaw('count(*) desc')->orderBy('schools.id')
            ->value('schools.id');
    }

    public static function school()
    {
        return School::query()->findOrFail(self::schoolId());
    }

    /** The approved teacher of that school with the most students. */
    public static function teacher()
    {
        $id = DB::table('teachers')
            ->join('teacher_users', 'teacher_users.teacher_id', '=', 'teachers.id')
            ->where('teachers.school_id', self::schoolId())
            ->where('teachers.active', 1)->where('teachers.approved', 1)
            ->whereNull('teachers.deleted_at')->whereNull('teacher_users.deleted_at')
            ->groupBy('teachers.id')
            ->orderByRaw('count(*) desc')->orderBy('teachers.id')
            ->value('teachers.id');

        return Teacher::query()->findOrFail($id);
    }

    /** The approved supervisor of that school with the most teachers. */
    public static function supervisor()
    {
        $id = DB::table('supervisors')
            ->join('supervisor_teachers', 'supervisor_teachers.supervisor_id', '=', 'supervisors.id')
            ->where('supervisors.school_id', self::schoolId())
            ->where('supervisors.active', 1)->where('supervisors.approved', 1)
            ->whereNull('supervisors.deleted_at')->whereNull('supervisor_teachers.deleted_at')
            ->groupBy('supervisors.id')
            ->orderByRaw('count(*) desc')->orderBy('supervisors.id')
            ->value('supervisors.id');

        return Supervisor::query()->findOrFail($id);
    }

    /**
     * The student with the most lesson and story tests among those who can
     * log in (active, not archived, subscription not ended). Every student of
     * school 740 is archived, so this is not one of its students: it is
     * student 1 of school 1 (15 lesson tests and a story test).
     */
    public static function student()
    {
        $id = DB::table('users')
            ->where('users.active', 1)->where('users.archived', 0)->whereNull('users.deleted_at')
            ->where('users.active_to', '>', '2026-10-22')
            ->orderByRaw('(select count(*) from user_tests where user_tests.user_id = users.id and user_tests.deleted_at is null) + (select count(*) from student_story_tests where student_story_tests.user_id = users.id and student_story_tests.deleted_at is null) desc')
            ->orderBy('users.id')
            ->value('users.id');

        return User::query()->findOrFail($id);
    }

    /**
     * Clears the forced password change flag and the password age of the four
     * panel accounts, so they reach their pages instead of the change
     * password screen. Must run inside the test's transaction (after
     * parent::setUp()), which rolls it back.
     */
    public static function unlockAccounts()
    {
        $accounts = [
            'managers' => self::manager()->id,
            'schools' => self::schoolId(),
            'teachers' => self::teacher()->id,
            'supervisors' => self::supervisor()->id,
        ];
        foreach ($accounts as $table => $id) {
            DB::table($table)->where('id', $id)->update(['force_password_change' => 0, 'password_changed_at' => null]);
        }
    }

    /**
     * Between two requests of one test: forget the logged in users and make
     * "web" the default guard again (actingAs() switches it, and a real
     * request would start from the configured default).
     */
    public static function resetAuth()
    {
        app('auth')->forgetGuards();
        app('auth')->shouldUse('web');
    }

    /** guest: the public pages, every login page, and the panels' pages as a redirect */
    public static function guestFilter()
    {
        return function ($uri, $route) {
            return true;
        };
    }

    public static function panelFilter($prefix)
    {
        return function ($uri, $route) use ($prefix) {
            return strpos($uri, $prefix . '/') === 0 && !preg_match('#/(login|password/reset)$#', $uri);
        };
    }

    /** The student pages are the User\* controllers of routes/web.php. */
    public static function studentFilter()
    {
        return function ($uri, $route) {
            return strpos(ltrim($route->getActionName(), '\\'), 'App\Http\Controllers\User\\') === 0;
        };
    }

    /** Every parameterless GET page that passes $filter, sorted. */
    public static function pages(callable $filter)
    {
        $uris = [];
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (!in_array('GET', $route->methods(), true) || strpos($uri, '{') !== false || preg_match(self::SKIP, $uri)) {
                continue;
            }
            if ($filter($uri, $route)) {
                $uris[] = $uri;
            }
        }
        sort($uris);

        return array_values(array_unique($uris));
    }

    private function check($guard, $user, callable $filter)
    {
        if ($guard !== 'guest') {
            $this->assertNotNull($user, "No {$guard} account found in the local database.");
        }

        $results = [];
        foreach (self::pages($filter) as $uri) {
            if ($user) {
                $this->actingAs($user, $guard);
            }

            if (getenv('SMOKE_VERBOSE')) {
                fwrite(STDERR, sprintf("\n[%s] %s %dMB", $guard, $uri, memory_get_usage(true) / 1048576));
            }

            $response = $this->get('/' . ltrim($uri, '/'));
            $status = $response->getStatusCode();
            $results[$uri] = $status;

            if ($status >= 500 && $response->exception) {
                fwrite(STDERR, sprintf("\n[%s] %s -> %d %s: %s", $guard, $uri, $status,
                    get_class($response->exception), strtok($response->exception->getMessage(), "\n")));
            }

            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        $baseline = $this->baseline();

        if (getenv('SMOKE_RECORD')) {
            $baseline[$guard] = $results;
            ksort($baseline);
            file_put_contents(base_path(self::BASELINE), json_encode($baseline, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
            $this->assertNotEmpty($results);

            return;
        }

        $this->assertArrayHasKey($guard, $baseline, 'Record the baseline first with SMOKE_RECORD=1.');

        $problems = [];
        foreach ($baseline[$guard] as $uri => $expected) {
            if (!array_key_exists($uri, $results)) {
                $problems[] = "{$uri}: missing (was {$expected})";
                continue;
            }
            $actual = $results[$uri];
            $fixed = $expected >= 500 && $actual < 500;
            if ($actual !== $expected && !$fixed) {
                $problems[] = "{$uri}: {$actual} (was {$expected})";
            }
        }
        foreach (array_diff_key($results, $baseline[$guard]) as $uri => $actual) {
            if ($actual >= 500) {
                $problems[] = "{$uri}: {$actual} (new page)";
            }
        }

        $this->assertSame([], $problems, "Pages that changed for {$guard}:\n" . implode("\n", $problems));
    }

    private function baseline()
    {
        $path = base_path(self::BASELINE);

        return is_file($path) ? json_decode(file_get_contents($path), true) : [];
    }
}
