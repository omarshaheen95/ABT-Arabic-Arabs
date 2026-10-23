<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * POST api/upload_image (route uploadImageLesson) stores an image under
 * public/uploads/lesson_images for the text editor of the manager's lesson,
 * story and assessment pages. Only a logged in manager may use it, with the
 * CSRF token those pages send.
 */
class UploadImageRouteTest extends TestCase
{
    use DatabaseTransactions;

    private $before;

    private $dirsBefore;

    private $root;

    protected function setUp(): void
    {
        parent::setUp();
        // Reported exceptions are posted to Telegram by this job (sync queue).
        \Illuminate\Support\Facades\Bus::fake([\App\Jobs\LogMessageJob::class]);
        SmokeRoutesTest::unlockAccounts();
        $this->root = public_path('uploads/lesson_images');
        $this->before = $this->uploaded();
        $this->dirsBefore = $this->dirs();
    }

    protected function tearDown(): void
    {
        $files = array_diff($this->uploaded(), $this->before);
        $dirs = array_diff($this->dirs(), $this->dirsBefore);
        parent::tearDown();
        // never leave behind the files this test uploaded, nor the folders it made
        foreach ($files as $file) {
            @unlink($file);
        }
        rsort($dirs); // the deepest first
        foreach ($dirs as $dir) {
            @rmdir($dir);
        }
    }

    private function uploaded()
    {
        return is_dir($this->root) ? array_map('realpath', glob($this->root.'/*/*/*/*') ?: []) : [];
    }

    private function dirs()
    {
        $dirs = is_dir($this->root) ? [realpath($this->root)] : [];
        foreach (['/*', '/*/*', '/*/*/*'] as $depth) {
            $dirs = array_merge($dirs, array_map('realpath', glob($this->root.$depth, GLOB_ONLYDIR) ?: []));
        }

        return $dirs;
    }

    private function image()
    {
        // a real temporary file: UploadedFile::fake() keeps a handle open on
        // it, which on Windows locks the folder it is moved to until the end
        $path = tempnam(sys_get_temp_dir(), 'upl');
        $image = imagecreatetruecolor(10, 10);
        imagepng($image, $path);
        imagedestroy($image);

        return new UploadedFile($path, 'upgrade-check.png', 'image/png', null, true);
    }

    public function test_a_guest_cannot_upload()
    {
        $response = $this->post('/api/upload_image', ['imageFile' => $this->image()]);

        $this->assertNotSame(200, $response->getStatusCode());
        $this->assertSame([], array_values(array_diff($this->uploaded(), $this->before)));
    }

    public function test_a_guest_cannot_upload_as_json()
    {
        $response = $this->postJson('/api/upload_image', ['imageFile' => $this->image()]);

        $this->assertNotSame(200, $response->getStatusCode());
        $this->assertSame([], array_values(array_diff($this->uploaded(), $this->before)));
    }

    public function test_another_panel_cannot_upload()
    {
        $this->actingAs(SmokeRoutesTest::school(), 'school');
        $response = $this->post('/api/upload_image', ['imageFile' => $this->image()]);

        $this->assertNotSame(200, $response->getStatusCode());
        $this->assertSame([], array_values(array_diff($this->uploaded(), $this->before)));
    }

    public function test_a_manager_can_still_upload()
    {
        $this->actingAs(SmokeRoutesTest::manager(), 'manager');
        $response = $this->post('/api/upload_image', ['imageFile' => $this->image(), '_token' => csrf_token()]);

        $response->assertOk();
        $link = $response->json('link');
        $this->assertMatchesRegularExpression('#/uploads/lesson_images/\d{4}/\d\d/\d\d/[0-9a-f]+\.png$#', $link);
        $this->assertCount(1, array_diff($this->uploaded(), $this->before));
    }
}
