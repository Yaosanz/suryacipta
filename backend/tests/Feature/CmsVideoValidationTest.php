<?php

namespace Tests\Feature;

use App\Rules\ValidCmsVideo;
use App\Support\VideoMetadata;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class CmsVideoValidationTest extends TestCase
{
    public function test_valid_16_by_9_hd_video_metadata_is_accepted(): void
    {
        $analyzer = Mockery::mock(VideoMetadata::class);
        $analyzer->shouldReceive('analyze')->once()->andReturn([
            'width' => 1280,
            'height' => 720,
            'duration' => 60.0,
            'size' => 1024,
            'mime_type' => 'video/mp4',
        ]);
        $this->app->instance(VideoMetadata::class, $analyzer);

        $failures = [];
        (new ValidCmsVideo)->validate(
            'video_path',
            $this->fakeMp4(),
            function (string $message) use (&$failures): void {
                $failures[] = $message;
            },
        );

        $this->assertSame([], $failures);
    }

    public function test_video_outside_resolution_ratio_and_duration_limits_is_rejected(): void
    {
        $analyzer = Mockery::mock(VideoMetadata::class);
        $analyzer->shouldReceive('analyze')->once()->andReturn([
            'width' => 1080,
            'height' => 1920,
            'duration' => 180.0,
            'size' => 1024,
            'mime_type' => 'video/mp4',
        ]);
        $this->app->instance(VideoMetadata::class, $analyzer);

        $failures = [];
        (new ValidCmsVideo)->validate(
            'video_path',
            $this->fakeMp4(),
            function (string $message) use (&$failures): void {
                $failures[] = $message;
            },
        );

        $this->assertCount(3, $failures);
        $this->assertStringContainsString('Resolusi', $failures[0]);
        $this->assertStringContainsString('Rasio', $failures[1]);
        $this->assertStringContainsString('Durasi', $failures[2]);
    }

    private function fakeMp4(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'cms-video-test-');
        file_put_contents($path, 'fake-mp4-content');

        return new class($path, 'video.mp4', 'video/mp4', null, true) extends UploadedFile
        {
            public function getMimeType(): ?string
            {
                return 'video/mp4';
            }
        };
    }
}
