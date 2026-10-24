<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): asks for a password reset link on
 * the four panels and for a student, records the notification each one sends
 * (subject, lines, link, rendered HTML), opens the reset form, resets the
 * password with the token and signs in with the new one. Written to
 * SNAPSHOT_DIR/password-reset.txt and password-reset-<guard>.html.
 */
class PasswordResetSnapshotTest extends TestCase
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

    public function test_reset_links()
    {
        // the same "now" on both versions and on both sides of midnight
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');
        Notification::fake();
        $dir = getenv('SNAPSHOT_DIR');
        $users = [
            'manager' => [SmokeRoutesTest::manager(), 'managers', \App\Notifications\ManagerResetPassword::class, '/manager'],
            'school' => [SmokeRoutesTest::school(), 'schools', \App\Notifications\SchoolResetPassword::class, '/school'],
            'teacher' => [SmokeRoutesTest::teacher(), 'teachers', \App\Notifications\TeacherResetPassword::class, '/teacher'],
            'supervisor' => [SmokeRoutesTest::supervisor(), 'supervisors', \App\Notifications\SupervisorResetPassword::class, '/supervisor'],
            'web' => [SmokeRoutesTest::student(), 'users', \Illuminate\Auth\Notifications\ResetPassword::class, ''],
        ];
        $out = [];
        foreach ($users as $guard => [$user, $broker, $class, $prefix]) {
            $r = $this->post("$prefix/password/email", ['email' => 'nobody@example.invalid']);
            $out[] = "[$guard] unknown email: status {$r->getStatusCode()}, location {$r->headers->get('Location')}, errors " . json_encode($this->errors(), JSON_UNESCAPED_UNICODE);
            $this->flushSession();

            $r = $this->post("$prefix/password/email", ['email' => $user->email]);
            $out[] = "[$guard] known email: status {$r->getStatusCode()}, status message " . json_encode(session('status'), JSON_UNESCAPED_UNICODE)
                . ', errors ' . json_encode($this->errors(), JSON_UNESCAPED_UNICODE);
            $this->flushSession();

            $sent = Notification::sent($user, $class);
            $out[] = "[$guard] notifications sent: " . count($sent);
            foreach ($sent as $notification) {
                $mail = $notification->toMail($user);
                $token = $notification->token;
                $url = str_replace($token, 'TOKEN', (string) $mail->actionUrl);
                $out[] = "[$guard] mail subject: " . $mail->subject . ', action: ' . $mail->actionText . ', url: ' . $url;
                $out[] = "[$guard] lines: " . json_encode(array_merge($mail->introLines, $mail->outroLines), JSON_UNESCAPED_UNICODE);
                file_put_contents("$dir/password-reset-$guard.html", str_replace($token, 'TOKEN', (string) $mail->render()));

                $form = $this->get(parse_url((string) $mail->actionUrl, PHP_URL_PATH) . (parse_url((string) $mail->actionUrl, PHP_URL_QUERY) ? '?' . parse_url((string) $mail->actionUrl, PHP_URL_QUERY) : ''));
                $out[] = "[$guard] reset form: status {$form->getStatusCode()}, has token field "
                    . json_encode(strpos((string) $form->getContent(), $token) !== false);
                $this->flushSession();

                $new = 'Upgrade-New-2026!';
                $r = $this->post("$prefix/password/reset", [
                    'token' => $token, 'email' => $user->email, 'password' => $new, 'password_confirmation' => $new,
                ]);
                $out[] = "[$guard] reset: status {$r->getStatusCode()}, location {$r->headers->get('Location')}, authenticated "
                    . json_encode(auth($guard)->check()) . ', errors ' . json_encode($this->errors(), JSON_UNESCAPED_UNICODE)
                    . ', status message ' . json_encode(session('status'), JSON_UNESCAPED_UNICODE);
                $out[] = "[$guard] new password works: " . json_encode(\Illuminate\Support\Facades\Hash::check($new, DB::table($broker)->where('id', $user->id)->value('password')));
                $this->flushSession();
                SmokeRoutesTest::resetAuth();
            }
            $table = config("auth.passwords.$broker.table");
            $out[] = "[$guard] token rows left in $table: " . DB::table($table)->where('email', $user->email)->count();
        }
        file_put_contents("$dir/password-reset.txt", implode("\n", $out) . "\n");
        $this->assertTrue(true);
    }

    private function errors()
    {
        $errors = session('errors');
        return is_object($errors) ? $errors->getBag('default')->toArray() : $errors;
    }
}
