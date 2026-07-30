<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

trait ClearsCmsCache
{
    protected static function bootClearsCmsCache(): void
    {
        static::saved(fn () => Cache::forget('cms.public.v1'));
        static::deleted(fn () => Cache::forget('cms.public.v1'));

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::restored(fn () => Cache::forget('cms.public.v1'));
        }
    }
}
