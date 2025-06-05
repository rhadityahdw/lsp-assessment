<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attempt extends Model
{
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    
    protected $fillable = [
        'user_id',
        'scheme_id',
        'ktp',
        'ijazah',
        'pas_foto',
        'bukti_kerja',
        'portofolio',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function preAssessmentAnswers()
    {
        return $this->hasMany(PreAssessmentAnswer::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }
}