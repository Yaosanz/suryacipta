<?php

namespace App\Models;

use App\Models\Concerns\ClearsCmsCache;
use App\Models\Concerns\RecordsDetailedActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class SiteSetting extends Model
{
    use ClearsCmsCache, LogsActivity, RecordsDetailedActivity;

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

        static::updated(function (SiteSetting $settings): void {
            $previousLogo = $settings->getOriginal('logo_path');

            if ($settings->wasChanged('logo_path') && filled($previousLogo)) {
                Storage::disk('public')->delete($previousLogo);
            }
        });

        static::deleted(function (SiteSetting $settings): void {
            if (filled($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('settings')->logFillable()->logOnlyDirty();
    }
}
