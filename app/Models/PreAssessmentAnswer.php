<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreAssessmentAnswer extends Model
{
    protected $guarded = [];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function preAssessment()
    {
        return $this->belongsTo(PreAssessment::class);
    }
}
