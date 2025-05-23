<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user dengan role asesi (role_id = 2)
        $asesiUsers = User::where('role_id', 2)->get();
        
        foreach ($asesiUsers as $index => $user) {
            // Data profil yang berbeda untuk setiap user
            $profiles = [
                [
                    'user_id' => $user->id,
                    'nik' => '1234567890' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    'gender' => $index % 2 == 0 ? 'male' : 'female',
                    'date_of_birth' => fake()->date('Y-m-d', '-20 years'),
                    'place_of_birth' => fake()->city(),
                    'address' => fake()->address(),
                    'phone_number' => '08' . fake()->numerify('##########'),
                    'education' => fake()->randomElement(['SMA', 'D3', 'S1', 'S2']),
                    'job_title' => fake()->jobTitle(),
                    'company_name' => fake()->company(),
                    'company_address' => fake()->address(),
                    'company_phone' => '021' . fake()->numerify('#######'),
                    'company_email' => 'info@' . fake()->domainName(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];
            
            DB::table('profiles')->insert($profiles);
        }
    }
}