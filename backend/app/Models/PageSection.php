<?php

namespace App\Models;

use App\Models\Concerns\ClearsCmsCache;
use App\Models\Concerns\HasCmsVideo;
use App\Models\Concerns\RecordsDetailedActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PageSection extends Model
{
    use ClearsCmsCache, HasCmsVideo, LogsActivity, RecordsDetailedActivity, SoftDeletes;

    protected $fillable = [
        'page', 'section_key', 'eyebrow', 'title', 'description', 'content',
        'image_path', 'image_alt', 'primary_cta_label', 'primary_cta_url',
        'is_enabled', 'sort_order',
        'video_path', 'video_alt', 'video_width', 'video_height',
        'video_duration', 'video_size',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_enabled' => 'boolean',
            'video_duration' => 'float',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('content')->logFillable()->logOnlyDirty();
    }
}
