<?php

namespace App\Services;

use App\Data\ProfileData;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Create a new profile for the authenticated user
     * 
     * @param User $user
     * @param ProfileData $profileData
     * @return Profile
     */
    public function createProfile(User $user, ProfileData $data)
    {
        return DB::transaction(function () use ($user, $data) {
            if($user->profile) {
                throw new \Exception('User already has a profile');
            }

            $profileData = $data->toArray();

            return Profile::create($profileData);
        });
    }

    /**
     * Update a profile for the authenticated user
     * 
     * @param Profile $profile
     * @param ProfileData $data
     * @return Profile
     */
    public function updateProfile(Profile $profile, ProfileData $data)
    {
        return DB::transaction(function () use ($profile, $data) {
            $profileData = $data->toArray();

            $profile->update($profileData);

            return $profile->refresh();
        });
    }

    /**
     * Delete a profile
     * 
     * @param Profile $profile
     * @return bool
     */
    public function deleteProfile(Profile $profile)
    {
        return DB::transaction(function () use ($profile) {
            return $profile->delete();
        });
    }
}