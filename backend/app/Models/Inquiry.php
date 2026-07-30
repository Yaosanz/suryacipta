<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Inquiry extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Fields intentionally exclude raw IP addresses and uploaded files.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'project_type',
        'estimated_area',
        'budget_range',
        'message',
        'attachment_path',
        'status',
        'source',
        'ip_hash',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('inquiries')
            ->logOnly(['status'])->logOnlyDirty()->dontLogEmptyChanges();
    }
}
