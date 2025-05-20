<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'nik',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'address',
        'phone_number',
        'education',
        'job_title',
        'company_name',
        'company_address',
        'company_phone_number',
        'company_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
