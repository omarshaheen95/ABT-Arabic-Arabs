<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        // The exception handler posts every reported error to Telegram through
        // this job, on the sync queue. Never send them from a test run.
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }
}
