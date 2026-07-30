<?php

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Models\Concerns\ClearsCmsCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class BlogPost extends Model
{
    use ClearsCmsCache, LogsActivity, SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'content', 'featured_image_path',
        'featured_image_alt', 'author_id', 'status', 'published_at',
        'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return ['status' => PublicationStatus::class, 'published_at' => 'datetime'];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published)
            ->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    protected static function booted(): void
    {
        static::creating(function (BlogPost $post): void {
            $post->author_id ??= auth()->id();
        });

        static::saving(function (BlogPost $post): void {
            if ($post->status === PublicationStatus::Published && $post->published_at === null) {
                $post->published_at = now();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('content')
            ->logOnly(['slug', 'title', 'excerpt', 'featured_image_path', 'author_id', 'status', 'published_at', 'seo_title', 'seo_description'])
            ->logOnlyDirty();
    }
}
