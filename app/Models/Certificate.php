<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scheme_id',
        'certificate_number',
        'issued_at',
        'expiry_date',
        'file_path',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expiry_date' => 'date',
    ];  

    public function asesi()
    {
        return $this->belongsTo(User::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}
