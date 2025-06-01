<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\User;
use App\Models\Scheme;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $asesi = User::role('asesi')->first();
        $scheme = Scheme::first();

        if ($asesi && $scheme) {
            Certificate::create([
                'user_id' => $asesi->id,
                'scheme_id' => $scheme->id,
                'certificate_number' => 'CERT/' . date('Y') . '/001',
                'issued_at' => now(),
                'expiry_date' => now()->addYears(3),
            ]);
        }
    }
}