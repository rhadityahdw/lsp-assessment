<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessments';

    protected $fillable = [
        'scheme_id',
        'name',
        'type',
        'link',
        'created_by',
    ];

    /**
     * Get the scheme that owns the assessment.
     */
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    /**
     * Get the schedule for the assessment.
     */
    // public function schedule()
}
