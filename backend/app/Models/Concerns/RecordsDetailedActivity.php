<?php

namespace App\Models\Concerns;

use App\Support\AuditContext;
use Spatie\Activitylog\Models\Activity;

trait RecordsDetailedActivity
{
    public function beforeActivityLogged(Activity $activity, string $eventName): void
    {
        $activity->description = match ($eventName) {
            'created' => 'Membuat data',
            'updated' => 'Mengubah data',
            'deleted' => 'Menghapus data',
            'restored' => 'Memulihkan data',
            default => ucfirst($eventName),
        };
        $activity->properties = $activity->properties->merge(AuditContext::capture($this));
    }
}
