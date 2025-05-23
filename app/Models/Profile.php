<?php

namespace App\Models;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gender' => Gender::class,
    ];

    /* Relationship */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
