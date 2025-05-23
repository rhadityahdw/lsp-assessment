<?php

namespace App\Models;

use App\Models\Assessment;
use App\Models\Attempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'assessment_id',
        'asesor_id',
        'asesi_id',
        'schedule_time',
        'location',
        'status'
    ];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function asesor()
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }

    public function asesi()
    {
        return $this->belongsTo(User::class, 'asesi_id');
    }
}
