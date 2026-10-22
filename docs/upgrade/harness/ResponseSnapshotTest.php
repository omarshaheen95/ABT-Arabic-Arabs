<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Upgrade harness (not part of the project): saves the normalized body of every
 * parameterless GET page of SmokeRoutesTest, as a page and as a datatables
 * ajax call, per guard (and for a guest), into SNAPSHOT_DIR so two versions of
 * the app can be diffed.
 */
class ResponseSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        // Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }

    public function test_snapshots()
    {
        // the same "now" on both versions and on both sides of midnight
        \Illuminate\Support\Carbon::setTestNow('2026-10-22 10:00:00');
        $dir = getenv('SNAPSHOT_DIR');
        $this->assertNotEmpty($dir);
        @mkdir($dir, 0777, true);

        $users = [
            'guest' => [null, SmokeRoutesTest::guestFilter()],
            'manager' => [SmokeRoutesTest::manager(), SmokeRoutesTest::panelFilter('manager')],
            'school' => [SmokeRoutesTest::school(), SmokeRoutesTest::panelFilter('school')],
            'teacher' => [SmokeRoutesTest::teacher(), SmokeRoutesTest::panelFilter('teacher')],
            'supervisor' => [SmokeRoutesTest::supervisor(), SmokeRoutesTest::panelFilter('supervisor')],
            'web' => [SmokeRoutesTest::student(), SmokeRoutesTest::studentFilter()],
        ];

        $count = 0;
        foreach ($users as $guard => [$user, $filter]) {
            foreach (SmokeRoutesTest::pages($filter) as $uri) {
                foreach (['page', 'ajax'] as $mode) {
                    if ($user) {
                        $this->actingAs($user, $guard);
                    }
                    if ($mode === 'ajax') {
                        $response = $this->get('/' . ltrim($uri, '/') . '?draw=1&start=0&length=10', [
                            'X-Requested-With' => 'XMLHttpRequest',
                            'Accept' => 'application/json, text/javascript, */*; q=0.01',
                        ]);
                    } else {
                        $response = $this->get('/' . ltrim($uri, '/'));
                    }
                    $name = $guard . '__' . $mode . '__' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $uri === '/' ? 'root' : $uri);
                    file_put_contents($dir . '/' . $name . '.txt', self::describe($response));
                    $this->flushSession();
                    SmokeRoutesTest::resetAuth();
                    $count++;
                }
            }
        }

        $this->assertGreaterThan(0, $count);
    }

    /** Status, type, redirect, exception and the normalized body of a response. */
    public static function describe($response)
    {
        try {
            $e = $response->exception;
        } catch (\Throwable $ignored) {
            $e = null;
        }
        $exception = $e ? get_class($e) . ': ' . strtok($e->getMessage(), "\n") : '';
        $base = $response->baseResponse;
        if ($base instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            $body = 'BINARY FILE ' . $response->headers->get('Content-Disposition') . ' ' . md5_file($base->getFile()->getPathname());
        } elseif ($base instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            $body = 'STREAMED ' . $response->headers->get('Content-Disposition');
        } else {
            $body = self::normalize((string) $response->getContent(), $response->headers->get('Content-Type'));
        }

        return "STATUS " . $response->getStatusCode() . "\nTYPE " . $response->headers->get('Content-Type') .
            "\nLOCATION " . $response->headers->get('Location') . "\nEXCEPTION " . $exception .
            "\nFLASH " . json_encode(self::flash(), JSON_UNESCAPED_UNICODE) . "\n\n" . $body;
    }

    /** The flashed message the layouts show after a redirect. */
    private static function flash()
    {
        $session = app('session.store');
        $out = [];
        foreach (['message', 'm-class', 'account-message'] as $key) {
            if ($session->has($key)) {
                $out[$key] = $session->get($key);
            }
        }
        $errors = $session->get('errors');
        if (is_object($errors) && method_exists($errors, 'getBag')) {
            $out['errors'] = $errors->getBag('default')->toArray();
        }

        return $out;
    }

    public static function normalize($body, $type)
    {
        if (strpos((string) $type, 'json') !== false) {
            $data = json_decode($body, true);
            if (is_array($data)) {
                unset($data['input'], $data['queries']);
                return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
        $body = preg_replace('/(name="_token"\s+value=")[^"]+/', '$1X', $body);
        $body = preg_replace('/(name="csrf-token"\s+content=")[^"]+/', '$1X', $body);
        $body = preg_replace("/('X-CSRF-TOKEN'\s*:\s*')[^']+/", '$1X', $body);
        $body = preg_replace('/("_token"\s*:\s*")[^"]+/', '$1X', $body);
        $body = preg_replace('/(_token=)[A-Za-z0-9]+/', '$1X', $body);

        return preg_replace("/[ \t]+\r?\n/", "\n", $body);
    }
}
