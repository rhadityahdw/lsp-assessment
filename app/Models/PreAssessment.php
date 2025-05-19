<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreAssessment extends Model
{
    use HasFactory;

    // Add this line to specify the table name
    protected $table = 'pre_assessments';

    protected $fillable = [
        'unit_id',
        'question',
        'expected_answer',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
