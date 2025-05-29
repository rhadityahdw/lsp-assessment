<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessments';

    protected $fillable = [
        'schedule_id',
        'scheme_id',
        'name',
        'type',
        'link',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the scheme that owns the assessment.
     */
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}
