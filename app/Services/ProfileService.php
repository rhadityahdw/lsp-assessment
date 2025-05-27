<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function createProfile(User $user, array $data): Profile
    {
        try {
            $profileData = array_merge($data, ['user_id' => $user->id]);
            $profile = Profile::create($profileData);

            return $profile;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create profile: ' . $e->getMessage());
        }
    }

    public function updateProfile(Profile $profile, array $data)
    {
        try {
            return DB::transaction(function () use ($profile, $data) {
                $cleanedData = array_map(function ($value) {
                    return $value === '' ? null : $value;
                }, $data);
    
                $profile->update($cleanedData);
    
                return $profile->fresh();
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update profile: '. $e->getMessage());
        }
    }

    public function getProfileWithRelations(User $user): ?Profile
    {
        return $user->load(['profile'])->profile;
    }
}