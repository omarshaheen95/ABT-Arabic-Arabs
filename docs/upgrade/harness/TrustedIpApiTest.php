<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * api/update_settings has no login: only callers from config('app.trusted_ips')
 * may use it. The caller is the TCP peer (REMOTE_ADDR, through TrustProxies),
 * never an address the client writes into a forwarding header itself.
 *
 * Runs on PHPUnit 9 (Laravel 8, @dataProvider) and 12 (the attribute).
 */
class TrustedIpApiTest extends TestCase
{
    use DatabaseTransactions;

    private const UNTRUSTED = '203.0.113.7';

    /** forwarding headers a web server hands to PHP as $_SERVER entries */
    private const SPOOFABLE = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED'];

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
    }

    protected function tearDown(): void
    {
        foreach (self::SPOOFABLE as $key) {
            unset($_SERVER[$key]);
        }
        parent::tearDown();
    }

    public static function spoofedHeaders()
    {
        return array_combine(self::SPOOFABLE, array_map(function ($key) { return [$key]; }, self::SPOOFABLE));
    }

    /**
     * @dataProvider spoofedHeaders
     */
    #[DataProvider('spoofedHeaders')]
    public function test_update_settings_refuses_a_forged_forwarding_header($serverKey)
    {
        $setting = Setting::query()->where('key', 'smtp_mail_host')->firstOrFail();

        $response = $this->spoofed($serverKey, '/api/update_settings', [
            'settings' => [$setting->key => 'mail.attacker.invalid'],
        ]);

        $response->assertStatus(401);
        $this->assertSame($setting->value, Setting::query()->find($setting->id)->value);
    }

    public function test_a_trusted_server_can_still_update_settings()
    {
        $setting = Setting::query()->where('key', 'note_date')->firstOrFail();
        $trusted = config('app.trusted_ips')[1];

        $response = $this->withServerVariables(['REMOTE_ADDR' => $trusted])
            ->postJson('/api/update_settings', ['settings' => [$setting->key => 'synced-value']]);

        $response->assertOk()->assertJsonPath('data.updated', [$setting->key]);
        $this->assertSame('synced-value', Setting::query()->find($setting->id)->value);
    }

    public function test_an_untrusted_server_is_refused_without_any_header()
    {
        $response = $this->withServerVariables(['REMOTE_ADDR' => self::UNTRUSTED])
            ->postJson('/api/update_settings', ['settings' => ['logo' => 'x']]);

        $response->assertStatus(401);
    }

    /**
     * A request from an untrusted address that claims, in a forwarding header,
     * to come from a trusted one: on the request, and in $_SERVER as PHP-FPM
     * would put it.
     */
    private function spoofed($serverKey, $uri, array $data)
    {
        $claimed = config('app.trusted_ips')[0];
        $_SERVER[$serverKey] = $claimed;
        $header = str_replace('_', '-', substr($serverKey, 5));

        return $this->withServerVariables(['REMOTE_ADDR' => self::UNTRUSTED, $serverKey => $claimed])
            ->withHeaders([$header => $claimed])
            ->json('POST', $uri, $data);
    }
}
