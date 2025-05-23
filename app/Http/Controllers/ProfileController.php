<?php

namespace App\Http\Controllers;

use App\Data\ProfileData;
use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display the user's profile
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Conversion if profile exists to ProfileData
        $profileData = $profile ? ProfileData::fromModel($profile) : null;
        
        return Inertia::render('UserProfile', [
            'profile' => $profileData,
        ]);
    }

    /**
     * Store a new profile for the authenticated user
     */
    public function store(ProfileData $data)
    {
        $user = Auth::user();

        try {
            $this->profileService->createProfile($user, $data);
            return redirect()->back()->with('success', 'Profile successfully created');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('profile', $e->getMessage());
        }
    }

    /**
     * Update the profile for the authenticated user
     */
    public function update(ProfileData $data)
    {
        $user = Auth::user();

        if(!$user->profile) {
            return redirect()->back()->withErrors('profile', 'Profile not found');
        }

        try {
            $this->profileService->updateProfile($user->profile, $data);
            return redirect()->back()->with('success', 'Profile successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'profile' => $e->getMessage()
            ]);
        }
    }
}
