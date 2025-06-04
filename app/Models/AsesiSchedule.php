<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsesiSchedule extends Model
{
    use HasFactory;

    protected $table = 'asesi_schedule';

    protected $fillable = [
        'asesi_id',
        'schedule_id',
        'score',
        'notes',
        'status'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'status' => 'string'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Penilaian',
            self::STATUS_APPROVED => 'Lulus',
            self::STATUS_REJECTED => 'Tidak Lulus'
        ];
    }

    // Relationships
    public function asesi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asesi_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}