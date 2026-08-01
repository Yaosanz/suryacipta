<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class MediaAssetMetadata
{
    /**
     * @return array{disk: string, original_name: string, mime_type: string, size: int, width: ?int, height: ?int, duration: ?float}
     */
    public function forPublicPath(string $path): array
    {
        $disk = Storage::disk('public');
        $absolutePath = $disk->path($path);
        $mimeType = (string) $disk->mimeType($path);
        $width = null;
        $height = null;
        $duration = null;

        if (str_starts_with($mimeType, 'image/')) {
            $dimensions = @getimagesize($absolutePath);
            $width = is_array($dimensions) ? (int) $dimensions[0] : null;
            $height = is_array($dimensions) ? (int) $dimensions[1] : null;
        } elseif ($mimeType === 'video/mp4') {
            $video = app(VideoMetadata::class)->analyze($absolutePath);
            $width = $video['width'];
            $height = $video['height'];
            $duration = $video['duration'];
        }

        return [
            'disk' => 'public',
            'original_name' => basename($path),
            'mime_type' => $mimeType,
            'size' => $disk->size($path),
            'width' => $width,
            'height' => $height,
            'duration' => $duration,
        ];
    }
}
