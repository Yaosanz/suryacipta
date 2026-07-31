<?php

namespace App\Support;

use RuntimeException;

class VideoMetadata
{
    /**
     * @return array{width: int, height: int, duration: float, size: int, mime_type: string}
     */
    public function analyze(string $path): array
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException('Berkas video tidak dapat dibaca.');
        }

        $information = (new \getID3)->analyze($path);
        $width = (int) data_get($information, 'video.resolution_x', 0);
        $height = (int) data_get($information, 'video.resolution_y', 0);
        $duration = (float) data_get($information, 'playtime_seconds', 0);
        $mimeType = (string) data_get($information, 'mime_type', '');

        if ($width < 1 || $height < 1 || $duration <= 0) {
            throw new RuntimeException('Metadata resolusi atau durasi video tidak dapat diverifikasi.');
        }

        return [
            'width' => $width,
            'height' => $height,
            'duration' => round($duration, 2),
            'size' => (int) filesize($path),
            'mime_type' => $mimeType,
        ];
    }
}
