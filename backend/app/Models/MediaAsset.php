<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class MediaAsset extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'disk', 'file_path', 'original_name', 'mime_type', 'size',
        'alt_text', 'caption', 'uploaded_by',
    ];

    protected static function booted(): void
    {
        static::forceDeleted(fn (MediaAsset $asset) => Storage::disk($asset->disk)->delete($asset->file_path));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('media')
            ->logOnly(['file_path', 'original_name', 'mime_type', 'size', 'alt_text', 'caption'])
            ->logOnlyDirty();
    }
}
