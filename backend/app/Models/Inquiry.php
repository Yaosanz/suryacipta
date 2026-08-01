<?php

namespace App\Models;

use App\Enums\InquiryStatus;
use App\Models\Concerns\RecordsDetailedActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Inquiry extends Model
{
    use HasFactory, LogsActivity, RecordsDetailedActivity;

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
        'handled_by',
        'handled_at',
        'source',
        'ip_hash',
    ];

    protected function casts(): array
    {
        return [
            'status' => InquiryStatus::class,
            'handled_at' => 'datetime',
        ];
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    protected static function booted(): void
    {
        static::saving(function (Inquiry $inquiry): void {
            if (
                $inquiry->status !== InquiryStatus::New
                && $inquiry->handled_at === null
                && auth()->check()
            ) {
                $inquiry->handled_by = auth()->id();
                $inquiry->handled_at = now();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('inquiries')
            ->logOnly(['status', 'handled_by', 'handled_at'])->logOnlyDirty()->dontLogEmptyChanges();
    }
}
