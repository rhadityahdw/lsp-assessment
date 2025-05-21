<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return Inertia::render('UserProfile', [
            'profile' => $profile,
        ]);
    }

    /**
     * Create a new profile for the authenticated user.
     *
     * POST /profiles
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16|unique:profiles,nik,' . Auth::id() . ',user_id',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'education' => 'required|string|max:50',
            'job_title' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:100',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:15',
            'company_email' => 'nullable|email|max:100',
        ]);

        $user = Auth::user();
        
        // Check if profile exists, update if it does, create if it doesn't
        if ($user->profile) {
            $user->profile->update($validated);
        } else {
            $profile = new Profile($validated);
            $profile->user_id = $user->id;
            $profile->save();
        }

        return redirect()->back()->with('success', 'Profil berhasil disimpan');
    }

    /**
     * Update the user's profile.
     *
     * PUT /profiles
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16|unique:profiles,nik,' . Auth::id() . ',user_id',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'education' => 'required|string|max:50',
            'job_title' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:100',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:15',
            'company_email' => 'nullable|email|max:100',
        ]);

        $user = Auth::user();
        $user->profile->update($validated);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
