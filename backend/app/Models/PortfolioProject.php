<?php

namespace App\Models;

use App\Models\Concerns\ClearsCmsCache;
use App\Models\Concerns\HasCmsVideo;
use App\Models\Concerns\RecordsDetailedActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PortfolioProject extends Model
{
    use ClearsCmsCache, HasCmsVideo, LogsActivity, RecordsDetailedActivity, SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'client_name', 'location', 'category', 'year',
        'description', 'image_path', 'image_alt', 'sort_order', 'is_featured',
        'is_published', 'published_at',
        'video_path', 'video_alt', 'video_width', 'video_height',
        'video_duration', 'video_size',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean', 'is_published' => 'boolean',
            'published_at' => 'datetime',
            'video_duration' => 'float',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    protected static function booted(): void
    {
        static::saving(function (PortfolioProject $project): void {
            if ($project->is_published && $project->published_at === null) {
                $project->published_at = now();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('content')->logFillable()->logOnlyDirty();
    }
}
