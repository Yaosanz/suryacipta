<?php

namespace App\Models;

use App\Models\Concerns\ClearsCmsCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Product extends Model
{
    use ClearsCmsCache, LogsActivity, SoftDeletes;

    protected $fillable = [
        'slug', 'name', 'category', 'short_description', 'description', 'features',
        'image_path', 'image_alt', 'sort_order', 'is_featured', 'is_published',
        'published_at', 'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array', 'is_featured' => 'boolean',
            'is_published' => 'boolean', 'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if ($product->is_published && $product->published_at === null) {
                $product->published_at = now();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('content')->logFillable()->logOnlyDirty();
    }
}
