<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Create a new profile for user.
     *
     * @param User $user
     * @param array $data
     * @return Profile
     */
    public function createProfile(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            if ($user->profile) {
                throw new \Exception('User already has a profile');
            }

            return Profile::create(array_merge($data, ['user_id' => $user->id]));
        });
    }

    /**
     * Update profile for user.
     *
     * @param Profile $profile
     * @param array $data
     * @return Profile
     */
    public function updateProfile(Profile $profile, array $data)
    {
        return DB::transaction(function () use ($profile, $data) {
            $profile->update($data);
            return $profile->refresh();
        });
    }

    /**
     * Delete profile for user.
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