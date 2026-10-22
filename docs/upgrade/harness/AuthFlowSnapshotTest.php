<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): walks the login / failed login /
 * lockout / logout / forced password change flow of every guard, a manager
 * logging in as a school, teacher, supervisor or student twice in the same
 * session, and a student's "remember me" cookie, and writes what happened to
 * SNAPSHOT_DIR/auth.txt. All changes are rolled back.
 */
class AuthFlowSnapshotTest extends TestCase
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

    private $log = [];

    public function test_flows()
    {
        // the same "now" on both versions and on both sides of midnight
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');
        $password = 'Upgrade-Check-2026!';
        $hash = Hash::make($password);

        $users = [
            'manager' => SmokeRoutesTest::manager(),
            'school' => SmokeRoutesTest::school(),
            'teacher' => SmokeRoutesTest::teacher(),
            'supervisor' => SmokeRoutesTest::supervisor(),
        ];
        $tables = ['manager' => 'managers', 'school' => 'schools', 'teacher' => 'teachers', 'supervisor' => 'supervisors'];

        foreach ($users as $guard => $user) {
            // the same password age everywhere, so expiry does not depend on the day of the run
            DB::table($tables[$guard])->where('id', $user->id)->update([
                'password' => $hash, 'force_password_change' => 0, 'password_changed_at' => now()->subDay(),
            ]);
            $before = (int) DB::table('login_sessions')->max('id');

            $r = $this->post("/$guard/login", ['email' => $user->email, 'password' => $password]);
            $this->record($guard, 'login ok', $r, ['authenticated' => auth($guard)->check()]);
            $home = $this->get($r->headers->get('Location') ?: "/$guard/home");
            $this->record($guard, 'follow redirect', $home);
            $again = $this->get("/$guard/profile/edit");
            $this->record($guard, 'second page', $again, ['authenticated' => auth($guard)->check()]);
            $r = $this->post("/$guard/logout");
            $this->record($guard, 'logout', $r, ['authenticated' => auth($guard)->check()]);
            $this->flushSession();
            SmokeRoutesTest::resetAuth();

            $r = $this->post("/$guard/login", ['email' => $user->email, 'password' => 'wrong-password']);
            $this->record($guard, 'login wrong password', $r, ['errors' => $this->errors()]);
            $this->flushSession();

            for ($i = 0; $i < 6; $i++) {
                $r = $this->post("/$guard/login", ['email' => $user->email, 'password' => 'wrong-password']);
            }
            $this->record($guard, 'after 7 wrong attempts', $r, ['errors' => $this->errors()]);
            $this->flushSession();
            app(\Illuminate\Cache\RateLimiter::class)->clear(\Illuminate\Support\Str::transliterate(strtolower($user->email) . '|127.0.0.1'));

            // a forced password change keeps the account on the password screen
            DB::table($tables[$guard])->where('id', $user->id)->update(['force_password_change' => 1]);
            $r = $this->post("/$guard/login", ['email' => $user->email, 'password' => $password]);
            $this->record($guard, 'login with forced change', $r, ['authenticated' => auth($guard)->check()]);
            $home = $this->get("/$guard/home");
            $this->record($guard, 'home with forced change', $home);
            $this->post("/$guard/logout");
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
            DB::table($tables[$guard])->where('id', $user->id)->update(['force_password_change' => 0]);

            if (in_array($guard, ['school', 'teacher', 'supervisor'], true)) {
                // an unapproved account
                $column = $guard === 'school' ? 'active' : 'approved';
                DB::table($tables[$guard])->where('id', $user->id)->update([$column => 0]);
                $r = $this->post("/$guard/login", ['email' => $user->email, 'password' => $password]);
                $this->record($guard, "login with $column = 0", $r, ['authenticated' => auth($guard)->check(), 'errors' => $this->errors()]);
                if (auth($guard)->check()) {
                    $this->record($guard, "home with $column = 0", $this->get("/$guard/home"));
                    $this->post("/$guard/logout");
                }
                $this->flushSession();
                SmokeRoutesTest::resetAuth();
                DB::table($tables[$guard])->where('id', $user->id)->update([$column => 1]);
            }

            $this->log[] = "[$guard] login_sessions rows written: " . json_encode($this->sessions($before), JSON_UNESCAPED_UNICODE);
        }

        // a manager logging in as another account, twice in the same session
        $manager = $users['manager'];
        $student = SmokeRoutesTest::student();
        foreach ([
            'school' => ["/manager/school/{$users['school']->id}/login", '/school/home'],
            'teacher' => ["/manager/teacher/{$users['teacher']->id}/login", '/teacher/home'],
            'supervisor' => ["/manager/supervisor/{$users['supervisor']->id}/login", '/supervisor/home'],
            'web' => ["/manager/user/{$student->id}/login", '/home'],
        ] as $guard => [$loginAs, $home]) {
            $this->actingAs($manager, 'manager');
            for ($round = 1; $round <= 2; $round++) {
                $r = $this->get($loginAs);
                $this->record('manager', "login as $guard ($round)", $r, ['authenticated' => auth($guard)->check()]);
                $page = $this->get($home);
                $this->record($guard, "home as manager ($round)", $page, ['authenticated' => auth($guard)->check()]);
                $page = $this->get($home);
                $this->record($guard, "home again as manager ($round)", $page, ['authenticated' => auth($guard)->check()]);
                SmokeRoutesTest::resetAuth();
            }
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }

        // students: email and password, archived, remember me
        $before = (int) DB::table('login_sessions')->max('id');
        DB::table('users')->where('id', $student->id)->update(['password' => $hash]);
        $r = $this->post('/login', ['email' => strtoupper($student->email), 'password' => $password]);
        $this->record('web', 'login ok', $r, ['authenticated' => auth('web')->check()]);
        $home = $this->get($r->headers->get('Location') ?: '/home');
        $this->record('web', 'follow redirect', $home);
        $r = $this->get('/logout');
        $this->record('web', 'logout', $r, ['authenticated' => auth('web')->check()]);
        $this->flushSession();
        SmokeRoutesTest::resetAuth();

        $r = $this->post('/login', ['email' => $student->email, 'password' => 'wrong-password']);
        $this->record('web', 'login wrong password', $r, ['errors' => $this->errors()]);
        $this->flushSession();
        $r = $this->post('/login', ['email' => 'nobody-' . $student->id . '@example.invalid', 'password' => $password]);
        $this->record('web', 'login unknown email', $r, ['errors' => $this->errors()]);
        $this->flushSession();
        app(\Illuminate\Cache\RateLimiter::class)->clear(\Illuminate\Support\Str::transliterate(strtolower($student->email) . '|127.0.0.1'));

        DB::table('users')->where('id', $student->id)->update(['archived' => 1]);
        $r = $this->post('/login', ['email' => $student->email, 'password' => $password]);
        $this->record('web', 'login archived', $r, ['authenticated' => auth('web')->check(), 'errors' => $this->errors()]);
        $this->flushSession();
        SmokeRoutesTest::resetAuth();
        DB::table('users')->where('id', $student->id)->update(['archived' => 0]);

        $r = $this->post('/login', ['email' => $student->email, 'password' => $password, 'remember' => 'on']);
        $remember = null;
        foreach ($r->headers->getCookies() as $cookie) {
            if (strpos($cookie->getName(), 'remember_web_') === 0) {
                $remember = $cookie;
            }
        }
        $this->record('web', 'login with remember me', $r, ['remember cookie' => (bool) $remember]);
        // a new browser session that only has the remember me cookie
        $this->flushSession();
        SmokeRoutesTest::resetAuth();
        if ($remember) {
            // the value is already encrypted by EncryptCookies: send it as it is
            $page = $this->withUnencryptedCookie($remember->getName(), $remember->getValue())->get('/home');
            $this->record('web', 'home from the remember me cookie only', $page, ['authenticated' => auth('web')->check()]);
        }
        $this->log[] = "[web] login_sessions rows written: " . json_encode($this->sessions($before), JSON_UNESCAPED_UNICODE);

        file_put_contents(getenv('SNAPSHOT_DIR') . '/auth.txt', implode("\n", $this->log) . "\n");
        $this->assertTrue(true);
    }

    private function sessions($afterId)
    {
        return DB::table('login_sessions')->where('id', '>', $afterId)->orderBy('id')->get()
            ->map(function ($row) {
                $row = (array) $row;
                unset($row['id'], $row['created_at'], $row['updated_at'], $row['user_agent']);
                return $row;
            })->all();
    }

    private function errors()
    {
        $errors = session('errors');
        if (is_object($errors)) {
            return $errors->getBag('default')->toArray();
        }
        return $errors;
    }

    private function record($guard, $step, $response, array $extra = [])
    {
        try {
            $e = $response->exception;
        } catch (\Throwable $ignored) {
            $e = null;
        }
        $flash = [];
        foreach (['message', 'm-class', 'account-message'] as $key) {
            if (session()->has($key)) {
                $flash[$key] = session($key);
            }
        }
        $this->log[] = sprintf('[%s] %s: status %d, location %s, exception %s %s %s',
            $guard, $step, $response->getStatusCode(), $response->headers->get('Location') ?: '-',
            $e ? get_class($e) . ': ' . strtok($e->getMessage(), "\n") : '-',
            $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : '',
            $flash ? json_encode($flash, JSON_UNESCAPED_UNICODE) : '');
    }
}
