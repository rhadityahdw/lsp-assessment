<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Get the schemes that owns the unit.
     */
    public function schemes(): BelongsToMany
    {
        return $this->belongsToMany(Scheme::class, 'scheme_units');
    }
    
    public function preAssessments()
    {
        return $this->hasMany(PreAssessment::class);
    }
}
