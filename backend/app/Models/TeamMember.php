<?php

namespace App\Models;

use App\Models\Concerns\ClearsCmsCache;
use App\Models\Concerns\RecordsDetailedActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class TeamMember extends Model
{
    use ClearsCmsCache, LogsActivity, RecordsDetailedActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'role',
        'bio',
        'photo_path',
        'photo_alt',
        'linkedin_url',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('content')->logFillable()->logOnlyDirty();
    }
}
