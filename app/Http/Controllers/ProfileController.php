<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {
        $this->middleware('permission:view profile')->only(['index']);
        $this->middleware('permission:edit profile')->only(['store', 'update']);
    }

    public function index()
    {
        $user = Auth::user();
        
        return Inertia::render('UserProfile', [
            'user' => $user->load(['roles', 'profile']),
            'profile' => $this->profileService->getProfileWithRelations($user),
        ]);
    }

    public function store(ProfileRequest $request)
    {
        try {
            $this->profileService->createProfile(Auth::user(), $request->validated());
            return redirect()->route('home')->with('success', 'Profile created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['profile' => $e->getMessage()]);
        }
    }

    public function update(ProfileRequest $request, Profile $profile)
    {
        try {
            $this->profileService->updateProfile($profile, $request->validated());
            return redirect()->route('home')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['profile' => $e->getMessage()]);
        }
    }
}
