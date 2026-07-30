<?php

namespace App\Models;

use App\Models\Concerns\ClearsCmsCache;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class SiteSetting extends Model
{
    use ClearsCmsCache, LogsActivity;

    protected $fillable = [
        'company_name', 'short_name', 'tagline', 'description', 'phone',
        'whatsapp', 'email', 'address', 'business_hours', 'social_links',
        'statistics', 'default_seo_title', 'default_seo_description',
        'logo_path', 'logo_alt', 'updated_by',
    ];

    protected function casts(): array
    {
        return ['address' => 'array', 'social_links' => 'array', 'statistics' => 'array'];
    }

    protected static function booted(): void
    {
        static::saving(function (SiteSetting $settings): void {
            $settings->updated_by = auth()->id();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('settings')->logFillable()->logOnlyDirty();
    }
}
