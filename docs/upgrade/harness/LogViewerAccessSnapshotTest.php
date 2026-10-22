<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): records who can open the log
 * viewer page (a guest and every guard) into SNAPSHOT_DIR/log-viewer.txt.
 * arcanedev/log-viewer (Laravel 8) and opcodesio/log-viewer (Laravel 13) have
 * different pages, so only the status and the redirect are compared.
 */
class LogViewerAccessSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }

    public function test_access()
    {
        $users = [
            'guest' => null,
            'manager' => SmokeRoutesTest::manager(),
            'school' => SmokeRoutesTest::school(),
            'teacher' => SmokeRoutesTest::teacher(),
            'supervisor' => SmokeRoutesTest::supervisor(),
            'web' => SmokeRoutesTest::student(),
        ];
        $out = [];
        foreach ($users as $guard => $user) {
            if ($user) {
                $this->actingAs($user, $guard);
            }
            $r = $this->get('/log-viewer');
            $out[] = "[$guard] /log-viewer: status {$r->getStatusCode()}, location " . ($r->headers->get('Location') ?: '-');
            $this->flushSession();
            SmokeRoutesTest::resetAuth();
        }
        file_put_contents(getenv('SNAPSHOT_DIR') . '/log-viewer.txt', implode("\n", $out) . "\n");
        $this->assertTrue(true);
    }
}
