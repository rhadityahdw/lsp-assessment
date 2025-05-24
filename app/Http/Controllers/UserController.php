<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = User::with('roles')->get();

        return Inertia::render('users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $roles = Role::pluck('name');

        return Inertia::render('users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_name' => $validated['role_name'],
        ];

        $this->userService->createUser($data);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name');

        return Inertia::render('users/Edit', [
            'user' => $user->load('roles'),
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_name' => 'required|string|exists:roles,name',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $validated = $request->validate($rules);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_name' => $validated['role_name'],
        ];

        if (isset($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $this->userService->updateUser($user, $data);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function show(User $user)
    {
        return Inertia::render('users/Show', [
            'user' => $user->load('roles'),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
