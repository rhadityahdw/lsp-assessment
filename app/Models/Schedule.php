<?php

namespace App\Models;

use App\Models\Assessment;
use App\Models\Attempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'asesor_id',
        'schedule_time',
        'location',
        'status'
    ];

    protected $casts = [
        'schedule_time' => 'datetime',
        'status' => 'string'
    ];

    // Status constants
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_SCHEDULED => 'Terjadwal',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    // Relationships
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }

    public function asesiSchedules(): HasMany
    {
        return $this->hasMany(AsesiSchedule::class);
    }

    // Add this missing relationship method
    public function asesis(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'asesi_schedule', 'schedule_id', 'asesi_id')
                    ->withPivot(['score', 'notes', 'status'])
                    ->withTimestamps();
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // Helper methods
    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeGraded(): bool
    {
        return $this->isCompleted();
    }
}
