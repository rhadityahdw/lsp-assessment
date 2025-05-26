<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        return Inertia::render('UserProfile', [
            'profile' => $profile,
        ]);
    }

    public function store(ProfileRequest $request)
    {
        $user = Auth::user();

        try {
            if($user->profile) {
                throw new \Exception('User already has a profile');
            }

            DB::transaction(function () use ($user, $request) {
                $profileData = $request->validated();
                $profileData['user_id'] = $user->id;
                
                Profile::create($profileData);
            });

            return redirect()->back()->with('success', 'Profile successfully created');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('profile', $e->getMessage());
        }
    }

    public function update(ProfileRequest $request, Profile $profile)
    {
        try {
            DB::transaction(function () use ($profile, $request) {
                $profileData = $request->validated();
                $profile->update($profileData);
            });

            return redirect()->back()->with('success', 'Profile successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('profile', $e->getMessage());
        }
    }
}
