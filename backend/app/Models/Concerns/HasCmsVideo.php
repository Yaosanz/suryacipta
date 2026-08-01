<?php

namespace App\Models\Concerns;

use App\Support\VideoMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait HasCmsVideo
{
    public static function bootHasCmsVideo(): void
    {
        static::saving(function (Model $model): void {
            if (! $model->isDirty('video_path')) {
                return;
            }

            if (blank($model->getAttribute('video_path'))) {
                $model->setAttribute('video_width', null);
                $model->setAttribute('video_height', null);
                $model->setAttribute('video_duration', null);
                $model->setAttribute('video_size', null);

                return;
            }

            $disk = Storage::disk('public');
            $path = (string) $model->getAttribute('video_path');

            if (! $disk->exists($path)) {
                return;
            }

            $metadata = app(VideoMetadata::class)->analyze($disk->path($path));
            $model->setAttribute('video_width', $metadata['width']);
            $model->setAttribute('video_height', $metadata['height']);
            $model->setAttribute('video_duration', $metadata['duration']);
            $model->setAttribute('video_size', $metadata['size']);
        });

        static::saved(function (Model $model): void {
            if (! $model->wasChanged('video_path')) {
                return;
            }

            $original = $model->getOriginal('video_path');

            if (filled($original) && $original !== $model->getAttribute('video_path')) {
                Storage::disk('public')->delete($original);
            }
        });

        static::forceDeleted(function (Model $model): void {
            if (filled($model->getAttribute('video_path'))) {
                Storage::disk('public')->delete($model->getAttribute('video_path'));
            }
        });
    }
}
