<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Scheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'document_path',
        'summary',
    ];

    /**
     * Get the units for the scheme.
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'scheme_units');
    }

    public function preAssessments()
    {
        return $this->hasManyThrough(
            PreAssessment::class,
            Unit::class,
            'scheme_id',
            'unit_id',
            'id',
            'id',
        );
    }
}
